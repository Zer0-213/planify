using WebApplication1.Common.DTOs;
using WebApplication1.User.DTOs;
using WebApplication1.User.Models;
using WebApplication1.User.Repositories;

namespace WebApplication1.User.Services;

public interface IUserService
{
    public GetUserDto GetUser(int id);
    public List<UserRoleInfoModel> GetUsersByCompany(int companyId);
}

public class UserService(IUserRepository userRepository) : IUserService
{
    public GetUserDto GetUser(int id)
    {
        var user = userRepository.QueryUserById(id);
        if (user == null)
        {
            throw new NullReferenceException("User not found");
        }
        return new GetUserDto
        {
                Id = user.Id,
                FirstName = user.FirstName,
                LastName = user.LastName,
                Email = user.Email,
                CompanyId = user.CompanyId
        };
    }
    
    public List<UserRoleInfoModel> GetUsersByCompany(int companyId)
    {
        return userRepository.QueryUsersByCompanyId(companyId);
    }
}