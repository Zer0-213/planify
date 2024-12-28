using WebApplication1.Authentication.DTOs;
using WebApplication1.Common.DTOs;

namespace WebApplication1.Authentication.Services;

public interface IAuthenticationService
{
    Response<SessionDto> AuthenticateCredentials(string email, string password);
    Response<RegisterDto> RegisterUser(RegisterDto registerDto);
}