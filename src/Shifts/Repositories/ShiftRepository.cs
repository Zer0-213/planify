using Microsoft.EntityFrameworkCore;
using WebApplication1.Data;
using WebApplication1.Shifts.Models;
using WebApplication1.User.Models;

namespace WebApplication1.Shifts.Repositories;


public interface IShiftRepository
{
    List<ShiftModel> QueryShiftsByCompanyId(int companyId, DateOnly startDate, DateOnly endDate);
}

public class ShiftRepository(AppDbContext dbContext) : IShiftRepository
{
    public List<ShiftModel> QueryShiftsByCompanyId(int companyId, DateOnly startDate, DateOnly endDate)
    {
        return dbContext.Shifts
            .Where(x => x.CompanyId == companyId)
            .Where(x => x.Date >= startDate && x.Date <= endDate)
            .Select(x => new ShiftModel
            {
                Id = x.Id,
                CompanyId = x.CompanyId,
                CreatedAt = x.CreatedAt,
                Date = x.Date,
                StartTime = x.StartTime,
                EndTime = x.EndTime,
                UserId = x.UserId,
                User = new UserModel
                {
                    Id = x.User.Id,
                    FirstName = x.User.FirstName,
                    LastName = x.User.LastName
                }
            })
            .ToList();
    }
}
