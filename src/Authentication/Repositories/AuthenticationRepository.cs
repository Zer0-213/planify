using Microsoft.Extensions.Caching.Memory;
using WebApplication1.Authentication.Models;
using WebApplication1.Common.Exceptions;
using WebApplication1.Data;
using WebApplication1.User;
using WebApplication1.Utils.Middleware;

namespace WebApplication1.Authentication.Repositories;

public class AuthenticationRepository(AppDbContext dbContext, IMemoryCache memoryCache) : IAuthenticationRepository
{

    public SessionModel? Authenticate(string email, string password)
    {
        var user = dbContext.Users
            .FirstOrDefault(u => u.Email == email);

        if (user == null) return null;

        if (!VerifyPassword(password, user.PasswordHashed)) return null; 

        var sessionId = Guid.NewGuid().ToString();
        var session = new SessionModel
        {
            UserId = user.Id,
            TokenHash = sessionId, 
            ExpiresAt = DateTime.UtcNow.AddDays(7) 
        };
        
        memoryCache.Set(sessionId, new SessionCacheModel
        {
            UserId = user.Id,
            ExpiresAt = session.ExpiresAt,
            CompanyId = user.CompanyId 
        }, session.ExpiresAt);

        return new SessionModel
        {
            TokenHash = sessionId, 
            ExpiresAt = session.ExpiresAt,
            UserId = user.Id
        };
    }

    public SessionModel CreateAccount(UserModel user)
    {
        var existingUser = dbContext.Users.FirstOrDefault(u => u.Email == user.Email);
        if (existingUser != null) throw new UserAlreadyExistsException(user.Email);

        user.PasswordHashed = HashString(user.PasswordHashed);
        dbContext.Users.Add(user);
        dbContext.SaveChanges();

        var sessionId = Guid.NewGuid().ToString();
        var session = new SessionModel
        {
            UserId = user.Id,
            TokenHash = sessionId, 
            ExpiresAt = DateTime.UtcNow.AddDays(7)
        };

        memoryCache.Set(sessionId, new SessionCacheModel
        {
            UserId = user.Id,
            ExpiresAt = session.ExpiresAt,
            CompanyId = user.CompanyId 
        }, session.ExpiresAt);

        return new SessionModel
        {
            TokenHash = sessionId, 
            ExpiresAt = session.ExpiresAt,
            UserId = user.Id
        };
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
