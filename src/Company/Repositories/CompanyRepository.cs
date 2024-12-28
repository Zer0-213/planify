using WebApplication1.Company.Models;
using WebApplication1.Data;

namespace WebApplication1.Company.Repositories;

public class CompanyRepository(AppDbContext appContext) : ICompanyRepository
{
    public bool CreateCompany(int userId, CompanyModel company)
    {
        appContext.Companies.Add(company);
        appContext.SaveChanges();


        var user = appContext.Users.SingleOrDefault(u => u.Id == userId);
        if (user == null) return false;

        user.CompanyId = company.Id;
        appContext.Users.Update(user);
        appContext.SaveChanges();

        return true;
    }

    public void UpdateCompany(CompanyModel company)
    {
        throw new NotImplementedException();
    }

    public void DeleteCompany(int companyId)
    {
        throw new NotImplementedException();
    }

    public CompanyModel GetCompany(int companyId)
    {
        throw new NotImplementedException();
    }

    public List<CompanyModel> GetCompanies()
    {
        throw new NotImplementedException();
    }
}