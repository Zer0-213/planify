using Microsoft.AspNetCore.Identity.Data;
using Microsoft.AspNetCore.Mvc;
using WebApplication1.Authentication.DTOs;
using WebApplication1.Authentication.Services;
using WebApplication1.Common.DTOs;
using WebApplication1.Utils.Middleware;

namespace WebApplication1.Authentication.Controllers;

[ApiController]
[Route("api/auth")]
public class AuthenticationController(IAuthenticationService authService) : ControllerBase, IAuthenticationController
{
    [HttpPost("login")]
    public ActionResult<Response<SessionDto>> Login(LoginRequest request)
    {
        try
        {
            var user = authService.AuthenticateCredentials(request.Email, request.Password);
            return Ok(user);
        }
        catch (UnauthorizedAccessException)
        {
            return Unauthorized(
                new Response<SessionDto>
                {
                    Success = false,
                    Error = new ErrorDto
                    {
                        Code = 401,
                        Message = "Unauthorized",
                        Description = "Invalid email or password"
                    }
                }
            );
        }
        catch (Exception e)
        {
            return StatusCode(502, new Response<SessionDto>
            {
                Success = false,
                Error = new ErrorDto
                {
                    Code = 502,
                    Message = "Bad Gateway",
                    Description = "An error occurred while processing your request"
                }
            });
        }
    }

    [ServiceFilter<UserAuthFilter>]
    [HttpPost("logout")]
    public ActionResult Logout()
    {
        HttpContext.Session.Clear();

        if (Request.Cookies.ContainsKey(".AspNetCore.Session")) Response.Cookies.Delete(".AspNetCore.Session");

        return Ok();
    }

    [HttpPost("register")]
    public ActionResult<Response<RegisterDto>> CreateAccount(RegisterDto registerDto)
    {
        return authService.RegisterUser(registerDto);
    }
}