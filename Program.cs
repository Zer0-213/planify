using Microsoft.EntityFrameworkCore;
using WebApplication1.Authentication.Services;
using WebApplication1.Company.Services;
using WebApplication1.Data;
using WebApplication1.User.Services;
using WebApplication1.Utils.Middleware;

var builder = WebApplication.CreateBuilder(args);

builder.Configuration
    .SetBasePath(Directory.GetCurrentDirectory())
    .AddJsonFile("appSettings.json", false, true)
    .AddJsonFile($"appSettings.{builder.Environment.EnvironmentName}.json", true, true)
    .AddEnvironmentVariables();

builder.Services.AddOpenApi();
builder.Services.AddDistributedMemoryCache();
builder.Services.AddMemoryCache();
builder.Services.AddSession(options =>
{
    options.IdleTimeout = TimeSpan.FromHours(24 * 7);
    options.Cookie.HttpOnly = true;
    options.Cookie.IsEssential = true;
});

builder.Services.AddDbContext<AppDbContext>(options =>
{
    options.UseNpgsql(builder.Configuration.GetConnectionString("DefaultConnection"));
});
// Add services to provider.
builder.Services.AddAuthenticationServices();
builder.Services.AddUserServices();
builder.Services.AddCompanyServices();


// Add controllers to providers.
builder.Services.AddControllers();

// Add middleware to providers.
builder.Services.AddScoped<UserAuthFilter>();

builder.Services.AddSession(options =>
{
    options.IdleTimeout = TimeSpan.FromMinutes(30);
    options.Cookie.HttpOnly = true;
    options.Cookie.IsEssential = true;
});


var app = builder.Build();

if (app.Environment.IsDevelopment()) app.MapOpenApi();

app.UseHttpsRedirection();
app.UseSession();

app.MapControllers();

app.Run();