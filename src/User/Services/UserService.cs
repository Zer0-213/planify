using WebApplication1.Common.DTOs;
using WebApplication1.User.DTOs;
using WebApplication1.User.Repositories;

namespace WebApplication1.User.Services;

public class UserService(IUserRepository userRepository) : IUserService
{
    public GetUserDto GetUser(int id)
    {
        var user = userRepository.QueryUserById(id);
        return new GetUserDto
        {
                Id = user.Id,
                FirstName = user.FirstName,
                LastName = user.LastName,
                Email = user.Email,
                CompanyId = user.CompanyId
        };
    }
}