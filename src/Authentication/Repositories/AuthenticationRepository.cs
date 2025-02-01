using Microsoft.Extensions.Caching.Memory;
using WebApplication1.Authentication.Models;
using WebApplication1.Common.Exceptions;
using WebApplication1.Data;
using WebApplication1.User.Models;

namespace WebApplication1.Authentication.Repositories;

public interface IAuthenticationRepository
{
    SessionCacheModel? Authenticate(string email, string password);
    SessionCacheModel CreateAccount(UserModel registerDto);
    SessionCacheModel? VerifySession(string? sessionId);
}

public class AuthenticationRepository(AppDbContext dbContext, IMemoryCache memoryCache) : IAuthenticationRepository
{

    public SessionCacheModel? Authenticate(string email, string password)
    {
        var user = dbContext.Users
            .FirstOrDefault(u => u.Email == email);

        if (user == null) return null;

        if (!VerifyPassword(password, user.PasswordHashed)) return null;


        var sessionCache = new SessionCacheModel
        {
            UserId = user.Id,
            CompanyId = user.CompanyId,
            ExpiresAt = DateTime.UtcNow.AddDays(7),
            Token = Guid.NewGuid().ToString()
        };

        memoryCache.Set(sessionCache.Token, sessionCache, sessionCache.ExpiresAt);

        return sessionCache;
    }

    public SessionCacheModel CreateAccount(UserModel user)
    {
        var existingUser = dbContext.Users.FirstOrDefault(u => u.Email == user.Email);
        if (existingUser != null) throw new UserAlreadyExistsException(user.Email);

        user.PasswordHashed = HashString(user.PasswordHashed);
        dbContext.Users.Add(user);
        dbContext.SaveChanges();

        var sessionCache = new SessionCacheModel()
        {
            UserId = user.Id,
            CompanyId = user.CompanyId,
            ExpiresAt = DateTime.UtcNow.AddDays(7),
            Token = Guid.NewGuid().ToString()
        };


        memoryCache.Set(sessionCache.Token, sessionCache, sessionCache.ExpiresAt);

        return sessionCache;
    }

    public SessionCacheModel? VerifySession(string? sessionId)
    {
        var found = memoryCache.TryGetValue<SessionCacheModel>(sessionId, out var sessionCache);

        if (!found || sessionCache == null) return null;

        if (sessionCache.ExpiresAt <= DateTime.UtcNow)
        {
            memoryCache.Remove(sessionId);
            return null;
        }
        
        return sessionCache;
    }

    private static bool VerifyPassword(string plainPassword, string hashedPassword)
    {
        return BCrypt.Net.BCrypt.Verify(plainPassword, hashedPassword);
    }

    private static string HashString(string plainPassword)
    {
        return BCrypt.Net.BCrypt.HashPassword(plainPassword);
    }
}
