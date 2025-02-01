using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.Filters;
using Microsoft.Extensions.Caching.Memory;
using WebApplication1.Authentication.Models;
using WebApplication1.Authentication.Repositories;
using WebApplication1.Common.DTOs;

namespace WebApplication1.Utils.Middleware;

public class UserAuthFilter(IAuthenticationRepository authRepo, bool checkCompanyId = true) : IAuthorizationFilter
{

    public void OnAuthorization(AuthorizationFilterContext context)
    {
        var httpContext = context.HttpContext;

        if (!httpContext.Request.Headers.TryGetValue("Authorization", out var sessionId))
        {
            context.Result = UnauthorizedResponse("No session cookie provided");
            return;
        }
        
        var session = authRepo.VerifySession(sessionId);
        if (session == null)
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
