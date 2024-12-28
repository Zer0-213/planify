using WebApplication1.Data;

namespace WebApplication1.User.Repositories;

public class UserRepository(AppDbContext dbContext) : IUserRepository
{
    public UserModel QueryUserById(int id)
    {
        return dbContext.Users.Find(id);
    }
}