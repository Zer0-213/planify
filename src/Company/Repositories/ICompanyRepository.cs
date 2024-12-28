using WebApplication1.Company.Models;

namespace WebApplication1.Company.Repositories;

public interface ICompanyRepository
{
    public bool CreateCompany(int userId, CompanyModel company);
    public void UpdateCompany(CompanyModel company);
    public void DeleteCompany(int companyId);
    public CompanyModel GetCompany(int companyId);
    public List<CompanyModel> GetCompanies();
}