using WebApplication1.Authentication.DTOs;
using WebApplication1.Authentication.Repositories;
using WebApplication1.User;

namespace WebApplication1.Authentication.Services;

public class AuthenticationService(IAuthenticationRepository authRepo) : IAuthenticationService
{
    public SessionDto AuthenticateCredentials(string email, string password)
    {
        var session = authRepo.Authenticate(email, password);

        if (session == null) throw new UnauthorizedAccessException();

        return new SessionDto
        {
                Token = session.TokenHash,
                ExpiresAt = session.ExpiresAt,
                UserId = session.UserId,
        };
    }

    public SessionDto RegisterUser(RegisterDto registerDto)
    {
        var result = authRepo.CreateAccount(new UserModel
        {
            FirstName = registerDto.FirstName,
            LastName = registerDto.LastName,
            Email = registerDto.Email,
            PasswordHashed = registerDto.Password,
            DateOfBirth = registerDto.DateOfBirth
        });

        return new SessionDto
        {
                Token = result.TokenHash,
                ExpiresAt = result.ExpiresAt,
                UserId = result.UserId,
        };
    }
}