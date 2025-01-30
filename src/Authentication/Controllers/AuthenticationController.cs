using Microsoft.AspNetCore.Identity.Data;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Caching.Memory;
using WebApplication1.Authentication.Services;
using WebApplication1.Common.DTOs;
using WebApplication1.Utils.Middleware;

namespace WebApplication1.Authentication.Controllers;

[ApiController]
[Route("api/auth")]
public class AuthenticationController(IMemoryCache memoryCache, IAuthenticationService authService) : ControllerBase
{

    [HttpPost("login")]
    public ActionResult Login(LoginRequest request)
    {
        try
        {
            var session = authService.AuthenticateCredentials(request.Email, request.Password);
            
            Response.Cookies.Append("SessionId", session.Token, new CookieOptions
            {
                HttpOnly = true,
                Secure = true,
                SameSite = SameSiteMode.Strict,
                Expires = session.ExpiresAt
            });
            
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

    [ServiceFilter(typeof(UserAuthFilter))]
    [HttpPost("logout")]
    public ActionResult Logout()
    {
        // Get session ID from cookie
        if (Request.Cookies.TryGetValue("SessionId", out var sessionId))
        {
            memoryCache.Remove(sessionId); // Remove session from cache
            Response.Cookies.Delete("SessionId"); // Remove cookie
        }

        return Ok(new { Message = "Logged out successfully" });
    }
}
