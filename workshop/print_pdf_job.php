<?php
    require ('../fpdf/fpdf.php');
    class PDF extends FPDF{
        function HEADER(){
            $this->SetFont('Arial','B',18);
            $this->Cell(60);
            $this->Cell(70,10,'Orden de Trabajo',0,1,'C');
            
            
            
             
        }
        function Footer(){
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);
            $this->Cell(0,10,utf8_decode('Pagina').$this->PageNo().'{nb}',0,0,'C');
        }
    }
    include('../database/conexion.php');
    
    
    $id_vehicle = $_GET['id'];
    $result_product = $conexion->query("SELECT C.name_client AS NCliente,
                                          C.carnet_identidad AS CCliente,
                                          C.telefono AS CTelefono,
                                          A.nombre AS NAsist,
                                          V.placa AS VPlaca,
                                          V.marca AS VMarca,
                                          V.modelo AS VModelo,
                                          V.color AS VColor,
                                          VI.fecha_ingreso AS FI,
                                          VI.fecha_salida AS FS,
                                          VI.costo_inventario AS CI,
                                          VI.costo_trabajo AS CT,
                                          VI.tipo_trabajo AS TT,
                                          VI.descuento_inventario AS DI,
                                          VI.descuento_trabajo AS DT,
                                          VI.adelantos AS AJ,
                                          VI.adelantos_inventario AS AI,
                                          (VI.costo_inventario-(VI.adelantos_inventario+VI.descuento_inventario)) AS DDI,
                                          (VI.costo_trabajo-(VI.adelantos+VI.descuento_trabajo)) AS DDT
                                           
                              FROM vehicle_entry VI 
                              INNER JOIN clients C ON  VI.carnet_cliente = C.carnet_identidad 
                              INNER JOIN assistant A ON VI.id_assistant=A.id
                              INNER JOIN vehicle V ON VI.placa=V.placa
                              WHERE VI.id=$id_vehicle");
      $row_product = $result_product->fetch_assoc();
                          
     
    $indice=1;
    include('../database/conexion.php');
    $id_vehicle = $_GET['id'];
    $sql_vehicle =mysqli_query($conexion,"SELECT * 
    FROM assign_work A INNER JOIN jobs J ON A.id_job= J.id WHERE id_v=$id_vehicle");
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,10,"Cliente: ".$row_product['NCliente']." _____ Fecha: ".$row_product['FI']." _____ Nro: ".$id_vehicle,2,1,'C');
    $pdf->Cell(0,10,"Placa: ".$row_product['VPlaca']." _ Marca: ".$row_product['VPlaca']." _ Modelo: ".$row_product['VModelo']." _ Color: ".$row_product['VColor'],2,1,'C');
   
    $pdf->Cell(20,8,'#',1,0,'C',0);
    $pdf->Cell(115,8,'Trabajo',1,0,'C',0);
    $pdf->Cell(55,8,'Costo',1,1,'C',0);
    while($vehicle=mysqli_fetch_array($sql_vehicle)){                 
        
        $pdf->Cell(20,8,$indice,1,0,'C',0);
        $pdf->Cell(115,8,$vehicle['trabajo'],1,0,'C',0);
        $pdf->Cell(55,8,$vehicle['price_job'],1,1,'C',0);  
        
        $indice++;
    }
    $pdf->Cell(190,8,'',1,1,'C',0);  
    $pdf->Cell(135,8,'Sub Total',1,0,'C',0);  
    $pdf->Cell(55,8,$row_product['CT'],1,1,'C',0);
    $pdf->Cell(135,8,'Descuento',1,0,'C',0);  
    $pdf->Cell(55,8,$row_product['DT'],1,1,'C',0);
    $pdf->Cell(135,8,'Adelanto',1,0,'C',0);  
    $pdf->Cell(55,8,$row_product['AJ'],1,1,'C',0);
    $pdf->Cell(135,8,'SALDO TOTAL',1,0,'C',0);  
    $pdf->Cell(55,8,$row_product['DDT'],1,1,'C',0);
    
    
    $pdf->Output();

?>