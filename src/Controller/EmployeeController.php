<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Controller responsible for handling all employee-related operations.
#[Route('/employee', name: 'employee_')]
final class EmployeeController extends AbstractController
{
    // Display a list of all employees.
    #[Route('/', name: 'list')]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();
        $numberOfEmployees = count($employees);

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
            'numberOfEmployees' => $numberOfEmployees,
        ]);
    }

    // Display the details of a single employee.
    #[Route("/{id}", name: "show", requirements: ["id" => "\d+"])]
    public function showEmployee(int $id, EmployeeRepository $employeeRepository): Response
    {
        $employee = $employeeRepository->find($id);

        if (!$employee) {
            throw $this->createNotFoundException("No employee found with ID " . $id);
        }

        return $this->render('employee/show.html.twig', [
            'employee' => $employee,
        ]);
    }

    // Filter employees by the first letter of their last name.
    #[Route('/findByFirstLetter-{firstLetter}', name: 'findByFirstLetter')]
    public function findByFirstLetter(EmployeeRepository $employeeRepository, string $firstLetter = null): Response
    {
        $employees = $firstLetter
            ? $employeeRepository->findByFirstLetter($firstLetter)
            : $employeeRepository->findAll();

        $numberOfEmployees = count($employees);

        return $this->render("employee/index.html.twig", [
            'employees' => $employees,
            'selectedLetter' => $firstLetter,
            'numberOfEmployees' => $numberOfEmployees,
        ]);
    }

    // Create a new employee.
    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employee);
            $entityManager->flush();

            $this->addFlash('success', 'Employee created successfully!');
            return $this->redirectToRoute('employee_list');
        }

        return $this->render('employee/manage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Edit an existing employee.
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, EmployeeRepository $employeeRepository, EntityManagerInterface $entityManager): Response
    {
        $employee = $employeeRepository->find($id);

        if (!$employee) {
            throw $this->createNotFoundException("Employee not found.");
        }

        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Employee successfully updated!');
            return $this->redirectToRoute('employee_list');
        }

        return $this->render('employee/manage.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee,
        ]);
    }
}
