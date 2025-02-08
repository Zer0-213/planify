using WebApplication1.Data;
using WebApplication1.Roles;
using WebApplication1.User.Models;

namespace WebApplication1.User.Repositories;

public interface IUserRepository
{
    UserModel? QueryUserById(int id);
    List<UserRoleInfoModel> QueryUsersByCompanyId(int companyId);
}

public class UserRepository(AppDbContext dbContext) : IUserRepository
{
    public UserModel? QueryUserById(int id)
    {
        return dbContext.Users.Find(id);
    }

    public List<UserRoleInfoModel> QueryUsersByCompanyId(int companyId)
    {
        return dbContext.UserRoles
            .Where(ur => ur.User.CompanyId == companyId)
            .GroupBy(ur => new
            {
                ur.UserId,
                ur.User.FirstName,
                ur.User.LastName,
                ur.User.Email,
                ur.User.PhoneNumber
            })
            .Select(group => new UserRoleInfoModel
            {
                UserId = group.Key.UserId,
                FirstName = group.Key.FirstName,
                LastName = group.Key.LastName,
                Email = group.Key.Email,
                PhoneNumber = group.Key.PhoneNumber,
                Roles = group.Select(ur => new RolesModel
                {
                    Id = ur.RoleId,
                    Name = ur.Role.Name,
                    PermissionId = ur.Role.PermissionId,
                    Description = ur.Role.Description
                }).ToList()
            })
            .ToList();
    }
}