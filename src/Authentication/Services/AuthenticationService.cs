using WebApplication1.Authentication.DTOs;
using WebApplication1.Authentication.Repositories;
using WebApplication1.User;

namespace WebApplication1.Authentication.Services;

public interface IAuthenticationService
{
    SessionDto AuthenticateCredentials(string email, string password);
    SessionDto RegisterUser(RegisterDto registerDto);
}

public class AuthenticationService(IAuthenticationRepository authRepo) : IAuthenticationService
{
    public SessionDto AuthenticateCredentials(string email, string password)
    {
        var session = authRepo.Authenticate(email, password);

        if (session == null) throw new UnauthorizedAccessException();

        return new SessionDto
        {
                Token = session.Token,
                ExpiresAt = session.ExpiresAt,
                UserId = session.UserId,
                CompanyId = session.CompanyId
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
                Token = result.Token,
                ExpiresAt = result.ExpiresAt,
                UserId = result.UserId,
                CompanyId = null
        };
    }
}