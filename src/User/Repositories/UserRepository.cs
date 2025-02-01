using WebApplication1.Data;
using WebApplication1.User.Models;

namespace WebApplication1.User.Repositories;

public class UserRepository(AppDbContext dbContext) : IUserRepository
{
    public UserModel QueryUserById(int id)
    {
        return dbContext.Users.Find(id);
    }
}