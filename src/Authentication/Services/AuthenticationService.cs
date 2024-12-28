using WebApplication1.Authentication.DTOs;
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

    public Response<RegisterDto> RegisterUser(RegisterDto registerDto)
    {
        var result = authRepo.CreateAccount(new UserModel
        {
            FirstName = registerDto.FirstName,
            LastName = registerDto.LastName,
            Email = registerDto.Email,
            PasswordHashed = registerDto.Password,
            DateOfBirth = registerDto.DateOfBirth
        });

        return result switch
        {
            CreateAccountResult.Failure => new Response<RegisterDto>
            {
                Success = false,
                Error = new ErrorDto
                {
                    Code = 500,
                    Message = "Internal Server Error",
                    Description = "An error occurred while creating the account"
                }
            },
            CreateAccountResult.UserAlreadyExists => new Response<RegisterDto>
            {
                Success = false,
                Error = new ErrorDto
                {
                    Code = 409,
                    Message = "Conflict",
                    Description = "A user with the provided email already exists"
                }
            },
            _ => new Response<RegisterDto> { Success = true, Data = registerDto }
        };
    }
}