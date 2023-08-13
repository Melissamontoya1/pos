<?php

$nw = new Northwind();
$id = 42;
class pdf extends FPDF
{
	public function header()
	{
		$this->SetFillColor(253, 135,39);
		$this->Rect(0,0, 220, 50, 'F');
		$this->SetY(25);
		$this->SetFont('Arial', 'B', 30);
		$this->SetTextColor(255,255,255);
		$this->Write(5, 'CodeStack');

	}

	public function footer()
	{
		$this->SetFillColor(253, 135,39);
		$this->Rect(0, 250, 220, 50, 'F');
		$this->SetY(-20);
		$this->SetFont('Arial', '', 12);
		$this->SetTextColor(255,255,255);
		$this->SetX(120);
		$this->Write(5, 'CodeStack');
		$this->Ln();
		$this->SetX(120);
		$this->Write(5, 'jose.jairo.fuentes@gmail.com');
		$this->SetX(120);
		$this->Ln();
		$this->SetX(120);
		$this->Write(5, '+(503)7889-8787');
	}
}

$fpdf = new pdf('P','mm','letter',true);
$fpdf->AddPage('portrait', 'letter');
$fpdf->SetMargins(10,30,20,20);
$fpdf->SetFont('Arial', '', 9);
$fpdf->SetTextColor(255,255,255);
$order = $nw->getOrder($id);
$customer = $nw->getCustomer($order->customer_id);
$order_details = $nw->getOrderDetails($order->id);

$fpdf->SetY(15);
$fpdf->SetX(120);
$fpdf->Write(5, 'DETALLES DEL ENVÍO ');
$fpdf->Ln();
$fpdf->SetX(120);
$fpdf->Write(5, 'Fecha de la orden: '.$order->order_date);
$fpdf->Ln();
$fpdf->SetX(120);
$fpdf->Write(5, 'Fecha de envío: '.$order->shipped_date);
$fpdf->Ln();
$fpdf->SetX(120);
$fpdf->Write(5, 'Dirección: '.$order->ship_address);
$fpdf->Ln();
$fpdf->SetX(120);
$fpdf->Write(5, 'Ciudad: '.$order->ship_address);

$fpdf->SetTextColor(0,0,0);
$fpdf->Image('img/2.png', 20, 55);

$fpdf->SetY(100);
$fpdf->SetTextColor(255,255,255);
$fpdf->SetFillColor(79,78,77);
$fpdf->Cell(60, 10, 'PRODUCTO', 0, 0, 'C', 1);
$fpdf->Cell(60, 10, 'DESCRIPCION', 0, 0, 'C', 1);
$fpdf->Cell(20, 10, 'P. UNITARIO', 0, 0, 'C', 1);
$fpdf->Cell(20, 10, 'CANTIDAD', 0, 0, 'C', 1);
$fpdf->Cell(30, 10, 'SUBTOTAL', 0, 0, 'C', 1);
$fpdf->Ln();

$fpdf->SetTextColor(0,0,0);
$fpdf->SetFillColor(255,255,255);
foreach($order_details as $detail)
{
	$fpdf->Cell(60, 10, $detail->product_name, 0, 0, 'C', 1);
	$fpdf->Cell(60, 10, $detail->description, 0, 0, 'C', 1);
	$fpdf->Cell(20, 10, $detail->unit_price, 0, 0, 'C', 1);
	$fpdf->Cell(20, 10, $detail->quantity, 0, 0, 'C', 1);
	$fpdf->Cell(30, 10, $detail->uni_price * $detail->quantity, 0, 0, 'C', 1);
	$fpdf->Ln();
}
$fpdf->OutPut();