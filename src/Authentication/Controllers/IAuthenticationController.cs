using Microsoft.AspNetCore.Identity.Data;
using Microsoft.AspNetCore.Mvc;
using WebApplication1.Authentication.DTOs;
using WebApplication1.Common.DTOs;

namespace WebApplication1.Authentication.Controllers;

public interface IAuthenticationController
{
    ActionResult<SessionDto> Login(LoginRequest request);
    ActionResult Logout();
    ActionResult<RegisterDto> CreateAccount(RegisterDto registerDto);
}