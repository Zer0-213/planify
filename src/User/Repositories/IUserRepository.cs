using WebApplication1.User.Models;

namespace WebApplication1.User.Repositories;

public interface IUserRepository
{
    UserModel QueryUserById(int id);
}