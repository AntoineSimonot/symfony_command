<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Invoice;
use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\CompanyRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Registry;


#[Route('/order')]
class OrderController extends AbstractController
{
    /**
     * @var Registry
     */
    private $workflows;

    public function __construct(Registry $workflows)
    {
        $this->workflows = $workflows;
    }

    #[Route('/', name: 'order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository, Request $request): Response
    {
        $state = $request->get('state') ? $request->get('state') : "*";
        $orders = $state == "*" ? $orderRepository->findAll() : $orderRepository->findByState($state);
        return $this->render('order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/not_processed', name: 'not_processed', methods: ['GET'])]
    public function not_processed(OrderRepository $orderRepository): Response
    {
        return $this->render('order/not_processed_order_list.html.twig', [
            'orders' => $orders,
        ]);
    }
    
    #[Route('/new', name: 'order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CompanyRepository $companyRepository): Response
    {
        $order = new Order();

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        $this->workflows->get($order, "order")->getMarking($order);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($order);
            $invoice = new Invoice();

            $company = $companyRepository->find(1);
            $invoice->setClientAddress($company->getAddress());
            $invoice->setClientName($company->getName());
            $invoice->setClientLogo($company->getLogo());
            $invoice->setCClientPhone($form->get('phone')->getData());
            $invoice->setCClientAddress($form->get('address')->getData());
            $invoice->setCClientName($form->get('client_name')->getData());
            $invoice->setNumber(rand(0,909888));
            $invoice->setCreatedAt(new \DateTimeImmutable());
            $order->addInvoice($invoice);

            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'order_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
    }
}
