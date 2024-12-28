using WebApplication1.User.Repositories;

namespace WebApplication1.User.Services;

public static class UserServiceExtension
{
    public static IServiceCollection AddUserServices(this IServiceCollection services)
    {
        services.AddScoped<IUserRepository, UserRepository>();
        services.AddScoped<IUserService, UserService>();
        return services;
    }
}