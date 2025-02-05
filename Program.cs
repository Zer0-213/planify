using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Caching.Memory;
using WebApplication1.Authentication.Services;
using WebApplication1.Company.Services;
using WebApplication1.Data;
using WebApplication1.Shifts.Services;
using WebApplication1.User.Services;
using WebApplication1.Utils.Middleware;

var builder = WebApplication.CreateBuilder(args);

builder.Configuration
    .SetBasePath(Directory.GetCurrentDirectory())
    .AddJsonFile("appSettings.json", false, true)
    .AddJsonFile($"appSettings.{builder.Environment.EnvironmentName}.json", true, true)
    .AddEnvironmentVariables();

builder.Services.AddOpenApi();
builder.Services.AddSingleton<IMemoryCache,MemoryCache>();

builder.Services.AddDbContext<AppDbContext>(options =>
{
    options.UseNpgsql(builder.Configuration.GetConnectionString("DefaultConnection"));
});
builder.Services.AddAuthenticationServices();
builder.Services.AddUserServices();
builder.Services.AddCompanyServices();
builder.Services.AddShiftService();


builder.Services.AddControllers();

builder.Services.AddScoped<UserAuthFilter>();

var app = builder.Build();

if (app.Environment.IsDevelopment()) app.MapOpenApi();


app.UseHttpsRedirection();

app.MapControllers();

app.Run();