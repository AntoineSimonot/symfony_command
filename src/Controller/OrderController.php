<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\InvoiceRow;
use App\Entity\Order;
use App\Form\OrderType;
use App\Helper\EmailHelper;
use App\Repository\CompanyRepository;
use App\Repository\OrderRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Registry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")]
#[Route('/order')]
class OrderController extends AbstractController
{
    /**
     * @var Registry
     */
    private Registry $workflows;

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

    #[Route('/new', name: 'order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CompanyRepository $companyRepository): Response
    {
        $order = new Order();

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->workflows->get($order, "order")->getMarking($order);
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
            $invoice->setCreatedAt(created_at: new DateTimeImmutable());
            $order->addInvoice($invoice);

            $total_amount = 0;
            foreach ($form->get('products')->getData() as $product) {
                $invoice_row = new InvoiceRow();
                $invoice_row->setProductName($product->getName());
                $invoice_row->setProductQuantity($product->getAmount());
                $invoice->addInvoiceRow($invoice_row);
                $total_amount = $total_amount + $product->getAmount();
            }
            $order->setTotalAmount($total_amount);
            $order->setTotalAmount($total_amount);

            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);


        }
        return $this->renderForm('order/new.html.twig', [
            'order' => $order,
            'form' => $form
        ]);

    }

    #[Route('/{id}/late', name: 'order_late_revive', methods: ['GET'])]
    public function sendLate(Order $order, EmailHelper $emailHelper, MailerInterface $mailer, $id): Response
    {
        $emailHelper->SendLateEmail($mailer, $order->getClientEmail(), $id);
        return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);

    }

    #[Route('/{id}', name: 'order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/{id}/send', name: 'order_send', methods: ['GET', 'POST'])]
    public function sendEmail($id,OrderRepository $orderRepository, Pdf $pdf, Order $order, MailerInterface $mailer, EntityManagerInterface $entityManager, EmailHelper $emailHelper): Response
    {
        $products = $order->getProducts();
        $invoices = $order->getInvoices();
        $lastInvoice = $orderRepository->findLastInvoice($id);
        $invoice = "invoice" . $lastInvoice . ".pdf";

        try {
            $findAmountOfPayment = $orderRepository->findAmountOfInvoice($order->getId());
        }
        catch (\Exception $exception) {
            $findAmountOfPayment = 0;
        }

        try {
            $pdf->generateFromHtml(
                $this->renderView('pdfs/invoice_pdf.html.twig', [
                    "products" => $products,
                    "order" => $order,
                    "invoices" => $invoices,
                    "findAmountOfPayment" => $findAmountOfPayment
                ]), $invoice
            );
        }
        catch (\Exception $exception) {
        }

        $invoicesDir = $this->getParameter('kernel.project_dir').'/public/'.$invoice;
        $emailHelper->SendInvoice($mailer, $order->getClientEmail(), $order->getId(), $invoicesDir);
        $order->setState('processed');
        $entityManager->persist($order);
        $entityManager->flush();


        return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
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
