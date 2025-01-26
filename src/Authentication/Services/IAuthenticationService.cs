using WebApplication1.Authentication.DTOs;
using WebApplication1.Common.DTOs;

namespace WebApplication1.Authentication.Services;

public interface IAuthenticationService
{
    SessionDto AuthenticateCredentials(string email, string password);
    SessionDto RegisterUser(RegisterDto registerDto);
}