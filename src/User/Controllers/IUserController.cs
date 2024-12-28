using Microsoft.AspNetCore.Mvc;
using WebApplication1.User.DTOs;

namespace WebApplication1.User.Controllers;

public interface IUserController
{
    ActionResult<GetUserDto> GetUser();
}