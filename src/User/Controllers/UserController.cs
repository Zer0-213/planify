using Microsoft.AspNetCore.Mvc;
using WebApplication1.User.DTOs;
using WebApplication1.User.Models;
using WebApplication1.User.Services;
using WebApplication1.Utils.Middleware;

namespace WebApplication1.User.Controllers;

public interface IUserController
{
    ActionResult<GetUserDto> GetUser();
}

[ApiController]
[Route("api/user")]
[TypeFilter(typeof(UserAuthFilter), Arguments = [false])]
public class UserController(IUserService userService) : ControllerBase, IUserController
{
    [Route("get-user")]
    [HttpGet]
    public ActionResult<GetUserDto> GetUser()
    {
        try
        {
            var id = (int)HttpContext.Items["userId"]!;

            var user = userService.GetUser(id);

            return Ok(user);
        }
        catch (NullReferenceException e)
        {
            Console.WriteLine(e.Message);
            return StatusCode(401, "Unauthorized");
        }
        catch (Exception e)
        {
            Console.WriteLine(e.Message);
            return StatusCode(500, "An error occurred while processing your request.");
        }
    }
    
    [Route("get-users-by-company")]
    [HttpGet]
    public ActionResult<List<UserRoleInfoModel>> GetUsersByCompany()
    {
        try
        {

            var companyId = HttpContext.Items["companyId"] as int? ?? throw new UnauthorizedAccessException("Company ID not found");
            
            var users = userService.GetUsersByCompany(companyId);

            return Ok(users);
        }
        catch (UnauthorizedAccessException)
        {
            return Unauthorized("Unauthorized");
        }
        catch (Exception e)
        {
            Console.WriteLine(e.Message);
            return StatusCode(500, "An error occurred while processing your request.");
        }
    }
}