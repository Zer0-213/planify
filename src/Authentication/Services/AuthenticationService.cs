using WebApplication1.Authentication.DTOs;
using WebApplication1.Authentication.Models;
using WebApplication1.Authentication.Repositories;
using WebApplication1.Common.DTOs;
using WebApplication1.User;

namespace WebApplication1.Authentication.Services;

public class AuthenticationService(IAuthenticationRepository authRepo) : IAuthenticationService
{
    public Response<SessionDto> AuthenticateCredentials(string email, string password)
    {
        var session = authRepo.Authenticate(email, password);

        if (session == null) throw new UnauthorizedAccessException();

        return new Response<SessionDto>
        {
            Success = true,
            Data = new SessionDto
            {
                Id = session.Id,
                Token = session.TokenHash,
                ExpiresAt = session.ExpiresAt
            }
        };
    }

    public Response<SessionDto> RegisterUser(RegisterDto registerDto)
    {
        var result = authRepo.CreateAccount(new UserModel
        {
            FirstName = registerDto.FirstName,
            LastName = registerDto.LastName,
            Email = registerDto.Email,
            PasswordHashed = registerDto.Password,
            DateOfBirth = registerDto.DateOfBirth
        });

        return new Response<SessionDto>
        {
            Success = true,
            Data = new SessionDto
            {
                Id = result.Id,
                Token = result.TokenHash,
                ExpiresAt = result.ExpiresAt
            }
        };
    }
}