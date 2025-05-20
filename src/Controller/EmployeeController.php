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

#[Route('/employee', name: 'employee_')]
final class EmployeeController extends AbstractController
{
    /**
     * Display a list of all employees.
     */
    #[Route('/', name: 'list')]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        // Retrieve all employees from the repository
        $employees = $employeeRepository->findAll();
        // Count the total number of employees
        $numberOfEmployees = count($employees);

        // Render the index template, passing employees and their count
        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
            'numberOfEmployees' => $numberOfEmployees,
        ]);
    }

    /**
     * Show details for a single employee by ID.
     */
    #[Route("/{id}", name: "show", requirements: ["id" => "\d+"])]
    public function showEmployee(int $id, EmployeeRepository $employeeRepository): Response
    {
        // Look up the employee with the specified ID
        $employee = $employeeRepository->find($id);

        // Throw a 404 error if the employee does not exist
        if (!$employee) {
            throw $this->createNotFoundException("No employee found with ID " . $id);
        }

        // Render the show template for the retrieved employee
        return $this->render('employee/show.html.twig', [
            'employee' => $employee,
        ]);
    }

    /**
     * Filter employees by the first letter of their last name.
     */
    #[Route('/findByFirstLetter-{firstLetter}', name: 'findByFirstLetter')]
    public function findByFirstLetter(EmployeeRepository $employeeRepository, string $firstLetter = null): Response
    {
        // Fetch employees filtered by initial letter if provided, otherwise fetch all
        $employees = $firstLetter
            ? $employeeRepository->findByFirstLetter($firstLetter)
            : $employeeRepository->findAll();
        // Count how many employees were found
        $numberOfEmployees = count($employees);

        // Render the index template with filtered employees and the selected letter
        return $this->render("employee/index.html.twig", [
            'employees' => $employees,
            'selectedLetter' => $firstLetter,
            'numberOfEmployees' => $numberOfEmployees,
        ]);
    }

    /**
     * Handle creation of a new employee.
     */
    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Initialize a new Employee entity
        $employee = new Employee();
        // Create and handle the employee form
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        // On valid submission, persist the new employee and redirect to list
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employee);
            $entityManager->flush();

            $this->addFlash('success', 'Employee created successfully!');
            return $this->redirectToRoute('employee_list');
        }

        // Render the form template for creating a new employee
        return $this->render('employee/manage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Handle editing of an existing employee.
     */
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, EmployeeRepository $employeeRepository, EntityManagerInterface $entityManager): Response
    {
        // Retrieve the employee to edit by ID
        $employee = $employeeRepository->find($id);

        // Throw a 404 error if the employee is not found
        if (!$employee) {
            throw $this->createNotFoundException("Employee not found.");
        }

        // Create and handle the edit form for the employee
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        // On valid submission, update the employee and redirect to list
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Employee successfully updated!');
            return $this->redirectToRoute('employee_list');
        }

        // Render the form template for editing an employee
        return $this->render('employee/manage.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee,
        ]);
    }
}
