using Microsoft.AspNetCore.Mvc;
using WebApplication1.Common.DTOs;
using WebApplication1.Company.DTOs;
using WebApplication1.Company.Services;
using WebApplication1.Utils.Middleware;

namespace WebApplication1.Company.Controllers;

[ApiController]
[Route("api/company")]
[TypeFilter(typeof(UserAuthFilter), Arguments = [false])]
public class CompanyController(ICompanyService companyService) : ControllerBase, ICompanyController
{
    [HttpPost("create-company")]
    public ActionResult<Response<string>> CreateCompany(CreateCompanyDto createCompany)
    {
        try
        {
            var userId = (int)(HttpContext.Items["userId"] ?? throw new InvalidOperationException());
            createCompany.UserId = userId;

            var success = companyService.CreateCompany(createCompany);
            if (!success) throw new Exception("An error occurred while creating the company.");

            return Ok(new Response<string>
            {
                Success = true,
                Data = "Company created successfully"
            });
        }
        catch (InvalidOperationException e)
        {
            Console.WriteLine(e.Message);
            return StatusCode(401, new Response<ErrorDto>
            {
                Success = false,
                Error = new ErrorDto
                {
                    Code = 401,
                    Message = "Unauthorized",
                    Description = "You are not authorized to perform this action."
                }
            });
        }
        catch (Exception e)
        {
            Console.WriteLine(e.Message);
            return StatusCode(500, new Response<ErrorDto>
            {
                Success = false,
                Error = new ErrorDto
                {
                    Code = 500,
                    Message = "Internal Server Error",
                    Description = "An error occurred while processing your request."
                }
            });
        }
    }
}