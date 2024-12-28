using WebApplication1.Company.Repositories;

namespace WebApplication1.Company.Services;

public static class AuthenticationServiceExtensions
{
    public static IServiceCollection AddCompanyServices(this IServiceCollection services)
    {
        services.AddScoped<ICompanyRepository, CompanyRepository>();
        services.AddScoped<ICompanyService, CompanyService>();
        return services;
    }
}