using Microsoft.AspNetCore.Identity.Data;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Caching.Memory;
using WebApplication1.Authentication.DTOs;
using WebApplication1.Authentication.Models;
using WebApplication1.Authentication.Services;
using WebApplication1.Common.DTOs;
using WebApplication1.Common.Exceptions;
using WebApplication1.Utils.Middleware;

namespace WebApplication1.Authentication.Controllers;

public interface IAuthenticationController
{
    ActionResult<SessionDto> Login(LoginRequest request);
    ActionResult Logout();
    ActionResult<SessionDto> Register(RegisterDto registerDto);
}

[ApiController]
[Route("api/auth")]
public class AuthenticationController(IAuthenticationService authService, IMemoryCache memoryCache) : ControllerBase,  IAuthenticationController
{

    [HttpPost("login")]
    public ActionResult<SessionDto> Login(LoginRequest request)
    {
        try
        {
            var session = authService.AuthenticateCredentials(request.Email, request.Password);
            
            return Ok(session);
        }
        catch (UnauthorizedAccessException)
        {
            return Unauthorized("Invalid email or password");
        }
        catch (Exception)
        {
            return StatusCode(502, new ErrResponse
            {
                Error = new ErrorDto
                {
                    Code = 502,
                    Message = "Bad Gateway",
                    Description = "An error occurred while processing your request"
                }
            });
        }
    }

    [HttpPost("register")]
    public ActionResult<SessionDto> Register(RegisterDto register)
    {
        try
        {
            var session = authService.RegisterUser(register);
            
            return Ok(session);
        } catch (UserAlreadyExistsException e)
        {
            return BadRequest(new ErrResponse
            {
                Error = new ErrorDto
                {
                    Code = 400,
                    Message = "Bad Request",
                    Description = e.Message
                }
            });
        }
        catch (Exception e)
        {
            Console.WriteLine(e);
            return StatusCode(502, new ErrResponse
            {
                Error = new ErrorDto
                {
                    Code = 502,
                    Message = "Bad Gateway",
                    Description = "An error occurred while processing your request"
                }
            });
        }
    }
    

    [ServiceFilter(typeof(UserAuthFilter))]
    [HttpPost("logout")]
    public ActionResult Logout()
    {
        if (!Request.Cookies.TryGetValue("SessionId", out var sessionId))
            return Ok(new { Message = "Logged out successfully" });
        
        memoryCache.Remove(sessionId); 
    
        return Ok(new { Message = "Logged out successfully" });
    }
}
