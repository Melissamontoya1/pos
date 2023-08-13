

<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar Bitácora
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar Bitácora</li>
    
    </ol>

  </section>

  <section class="content">
    <div class="box">

      <div class="box-header with-border">

        
         <button class="btn btn-danger btnEliminarCategoria" name="idBitacora" >

         Vaciar Bitacora

        </button>
      
      </div>

    <div class="box">

      <div class="box-header with-border">
  


      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablaBitacora" width="100%">
         
        <thead>
         
         <tr>
           <th>#</th>
           <th>Descripcion</th>
           <th>Fecha</th>
           <th>Usuario</th>

         </tr> 

        </thead>

        <tbody>

      

        </tbody>

       </table>

      </div>

    </div>

  </section>

</div>


<!--=====================================
ELIMINAR LA BITACORA
======================================-->



<?php

  $borrarBitacora = new ControladorBitacora();
  $borrarBitacora -> ctrBorrarBitacora();

?>


