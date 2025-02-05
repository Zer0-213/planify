using WebApplication1.Shifts.Repositories;

namespace WebApplication1.Shifts.Services;

public static class ShiftServiceExtension
{
    public static IServiceCollection AddShiftService(this IServiceCollection services)
    {
        services.AddScoped<IShiftRepository, ShiftRepository>();
        services.AddScoped<IShiftService, ShiftService>();
        return services;
    }
}