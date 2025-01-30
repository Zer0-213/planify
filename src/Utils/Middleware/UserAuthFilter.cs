using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.Filters;
using Microsoft.Extensions.Caching.Memory;
using WebApplication1.Authentication.Models;
using WebApplication1.Common.DTOs;

namespace WebApplication1.Utils.Middleware;

public class UserAuthFilter(IMemoryCache memoryCache, bool checkCompanyId = true) : IAuthorizationFilter
{
    private readonly IMemoryCache _memoryCache = memoryCache ?? throw new ArgumentNullException(nameof(memoryCache));

    public void OnAuthorization(AuthorizationFilterContext context)
    {
        var httpContext = context.HttpContext;

        if (!httpContext.Request.Cookies.TryGetValue("SessionId", out var sessionId))
        {
            context.Result = UnauthorizedResponse("No session cookie provided");
            return;
        }

        if (!_memoryCache.TryGetValue(sessionId, out SessionCacheModel? session) || session?.ExpiresAt <= DateTime.UtcNow)
        {
            context.Result = UnauthorizedResponse("Session expired or invalid");
            return;
        }

        if (checkCompanyId && session?.CompanyId == null)
        {
            context.Result = UnauthorizedResponse("User does not belong to a company");
            return;
        }
        
        httpContext.Items["userId"] = session?.UserId;
    }

    private static JsonResult UnauthorizedResponse(string description)
    {
        return new JsonResult(new ErrResponse
        {
            Error = new ErrorDto
            {
                Code = 401,
                Message = "Unauthorized",
                Description = description
            }
        })
        {
            StatusCode = 401
        };
    }
}
