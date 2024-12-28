using WebApplication1.Authentication.Models;
using WebApplication1.Data;
using WebApplication1.User;
using static System.Console;

namespace WebApplication1.Authentication.Repositories;

public class AuthenticationRepository(AppDbContext dbContext) : IAuthenticationRepository
{
    public SessionModel? Authenticate(string email, string password)
    {
        var user = dbContext.Users
            .FirstOrDefault(u => u.Email == email);

        if (user == null) return null;

        if (!VerifyPassword(password, user.PasswordHashed)) return null;

        var token = Guid.NewGuid().ToString();
        var session = new SessionModel
        {
            UserId = user.Id,
            TokenHash = HashString(token),
            ExpiresAt = DateTime.UtcNow.AddDays(7)
        };

        dbContext.Sessions.Add(session);
        dbContext.SaveChanges();

        return new SessionModel
        {
            Id = session.Id,
            TokenHash = token,
            ExpiresAt = session.ExpiresAt
        };
    }


    public CreateAccountResult CreateAccount(UserModel user)
    {
        try
        {
            var existingUser = dbContext.Users.FirstOrDefault(u => u.Email == user.Email);
            if (existingUser != null) return CreateAccountResult.UserAlreadyExists;

            user.PasswordHashed = HashString(user.PasswordHashed);
            dbContext.Users.Add(user);
            dbContext.SaveChanges();

            return CreateAccountResult.Success;
        }
        catch (Exception e)
        {
            WriteLine(e);
            return CreateAccountResult.Failure; // Handle unexpected errors
        }
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