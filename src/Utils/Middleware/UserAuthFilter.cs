using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.Filters;
using WebApplication1.Common.DTOs;
using WebApplication1.Data;
using WebApplication1.Utils.ResponseObjects;

namespace WebApplication1.Utils.Middleware;

public class UserAuthFilter(AppDbContext dbContext, bool checkCompanyId = true) : IAuthorizationFilter
{
    private readonly AppDbContext _dbContext = dbContext ?? throw new ArgumentNullException(nameof(dbContext));

    public void OnAuthorization(AuthorizationFilterContext context)
    {
        var httpContext = context.HttpContext;

        if (!httpContext.Request.Headers.TryGetValue("Authorization", out var sessionData))
        {
            context.Result = new JsonResult(new Response<UnauthorizedResponse>
            {
                Success = false,
                Error = new ErrorDto
                {
                    Code = 401,
                    Message = "Unauthorized",
                    Description = "No session id provided"
                }
            })
            {
                StatusCode = 401
            };
            return;
        }

        var sessions = _dbContext.Sessions.ToList();
        var session = sessions.FirstOrDefault(s => VerifyHash(sessionData.ToString(), s.TokenHash));
        if (session == null || session.ExpiresAt <= DateTime.UtcNow)
        {
            context.Result = new JsonResult(new Response<UnauthorizedResponse>
            {
                Success = false,
                Error = new ErrorDto
                {
                    Code = 401,
                    Message = "Unauthorized",
                    Description = "No session id provided or session expired"
                }
            })
            {
                StatusCode = 401
            };
            return;
        }

        var user = _dbContext.Users.Find(session.UserId);

        if (checkCompanyId && user?.CompanyId == null)
        {
            context.Result = new JsonResult(new Response<UnauthorizedResponse>
            {
                Success = false,
                Error = new ErrorDto
                {
                    Code = 401,
                    Message = "No company id",
                    Description = "User does not belong to a company"
                }
            })
            {
                StatusCode = 401
            };
            return;
        }

        httpContext.Items["userId"] = user!.Id;
    }

    private static bool VerifyHash(string plainText, string hashedText)
    {
        return BCrypt.Net.BCrypt.Verify(plainText, hashedText);
    }
}