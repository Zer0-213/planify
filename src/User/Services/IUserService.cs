using WebApplication1.Common.DTOs;
using WebApplication1.User.DTOs;

namespace WebApplication1.User.Services;

public interface IUserService
{
    public Response<GetUserDto> GetUser(int id);
}