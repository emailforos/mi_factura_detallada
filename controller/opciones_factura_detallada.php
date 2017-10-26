<?php

/*
 * This file is part of FacturaSctipts
 * Copyright (C) 2016      César Sáez Rodríguez    NATHOO@lacalidad.es
 * Copyright (C) 2016-2017 Carlos García Gómez     neorazorx@gmail.com
 * Copyright (C) 2016-2017 Rafael Salas            rsalas.match@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_model('idioma_fac_det.php');

/**
 * Description of opciones_factura_detallada
 *
 * @author César Sáez Rodríguez
 */
class opciones_factura_detallada extends fs_controller
{
   public $allow_delete;
   public $colores;
   public $factura_detallada_setup;
   public $idioma;
   
   public function __construct()
   {
      parent::__construct(__CLASS__, 'Factura Detallada', 'admin', FALSE, FALSE);
   }
   
   protected function private_core()
   {
      /// ¿El usuario tiene permiso para eliminar en esta página?
      $this->allow_delete = $this->user->allow_delete_on(__CLASS__);
      
      if( isset($_REQUEST['cod']) )
      {
         $this->editar_idioma();
      }
      else
      {
         $this->check_menu();
         $this->colores = array(
             'amarillo' => "amarillo",
             'azul' => "azul",
             'blanco' => "blanco",
             'gris' => "gris",
             'marron' => "marrón",
             'naranja' => "naranja",
             'personalizado' => "personalizado (RGB)",
             'rojo' => "rojo",
             'verde' => "verde"
         );
         
         /// cargamos la configuración
         $fsvar = new fs_var();
         $this->factura_detallada_setup = $fsvar->array_get(
            array(
               'f_detallada_color' => 'azul',
               'f_detallada_color_r' => '192',
               'f_detallada_color_g' => '0',
               'f_detallada_color_b' => '0',
               'f_detallada_print_may_min' => FALSE,
               'f_detallada_QRCODE' => FALSE,
               'f_detallada_observaciones_producto' => FALSE,
               'f_detallada_imprime_albaran' => FALSE,
               'f_detallada_agrupa_albaranes' => FALSE,
               'f_detallada_maquetar_negrita' => FALSE
            ),
            FALSE
         );
         
         $this->idioma = new idioma_fac_det();
         
         if( isset($_POST['factura_detallada_setup']) )
         {
            $this->factura_detallada_setup['f_detallada_color'] = $_POST['color'];
            
            if( isset($_POST['color_r']) )
            {
               $this->factura_detallada_setup['f_detallada_color_r'] = $_POST['color_r'];
               $this->factura_detallada_setup['f_detallada_color_g'] = $_POST['color_g'];
               $this->factura_detallada_setup['f_detallada_color_b'] = $_POST['color_b'];
            }
            
            $this->factura_detallada_setup['f_detallada_print_may_min'] = isset($_POST['print_may_min']);
            $this->factura_detallada_setup['f_detallada_QRCODE'] = isset($_POST['QRCODE']);
            $this->factura_detallada_setup['f_detallada_observaciones_producto'] = isset($_POST['observaciones_producto']);
            $this->factura_detallada_setup['f_detallada_imprime_albaran'] = isset($_POST['imprime_albaran']);
            $this->factura_detallada_setup['f_detallada_agrupa_albaranes'] = isset($_POST['agrupa_albaran']);
            $this->factura_detallada_setup['f_detallada_maquetar_negrita'] = isset($_POST['maquetar_negrita']);
            
            if( $fsvar->array_save($this->factura_detallada_setup) )
            {
               $this->new_message('Datos guardados correctamente.');
            }
            else
               $this->new_error_msg('Error al guardar los datos.');
         }
         else if( isset($_POST['codigo']) )
         {
            $this->nuevo_idioma();
         }
         else if( isset($_GET['delete_idioma']) )
         {
            $this->eliminar_idioma();
         }
         else
         {
            $this->share_extension();
         }
      }
   }
   
   private function share_extension()
   {
      $fsext = new fs_extension();
      $fsext->name = 'opciones_fac_detallada';
      $fsext->from = __CLASS__;
      $fsext->to = 'admin_empresa';
      $fsext->type = 'button';
      $fsext->text = '<span class="glyphicon glyphicon-print" aria-hidden="true"></span> &nbsp; Factura Detallada';
      $fsext->save();
      
      foreach($this->idioma->all() as $idi)
      {
         $fsext = new fs_extension();
         $fsext->name = 'factura_detallada_' . $idi->codidioma;
         $fsext->from = 'factura_detallada';
         $fsext->to = 'ventas_factura';
         $fsext->type = 'pdf';
         $fsext->text = '<span class="glyphicon glyphicon-print"></span>&nbsp; Factura detallada ' . $idi->codidioma;
         $fsext->params = '&codidioma=' . $idi->codidioma;
         
         if($idi->activo)
         {
            $fsext->save();
         }
         else
         {
            $fsext->delete();
         }

         $fsext2 = new fs_extension();
         $fsext2->name = 'enviar_factura_detallada_' . $idi->codidioma;
         $fsext2->from = 'factura_detallada';
         $fsext2->to = 'ventas_factura';
         $fsext2->type = 'email';
         $fsext2->text = 'Factura detallada ' . $idi->codidioma;
         $fsext2->params = '&codidioma=' . $idi->codidioma;
         
         if($idi->activo)
         {
            $fsext2->save();
         }
         else
         {
            $fsext2->delete();
         }
      }
   }
   
   /**
    * Activamos las páginas del plugin.
    */
   private function check_menu()
   {
      if( file_exists(__DIR__) )
      {
         /// activamos las páginas del plugin
         foreach( scandir(__DIR__) as $f)
         {
            if( $f != '.' AND $f != '..' AND is_string($f) AND strlen($f) > 4 AND !is_dir($f) AND $f != __CLASS__.'.php' )
            {
               $page_name = substr($f, 0, -4);
               
               require_once __DIR__.'/'.$f;
               $new_fsc = new $page_name();
                  
               if( !$new_fsc->page->save() )
               {
                  $this->new_error_msg("Imposible guardar la página ".$page_name);
               }
               
               unset($new_fsc);
            }
         }
      }
      else
      {
         $this->new_error_msg('No se encuentra el directorio '.__DIR__);
      }
   }
   
   private function nuevo_idioma()
   {
      $idioma = new idioma_fac_det();
      $idioma->codidioma = $_POST['codigo'];
      $idioma->nombre = $_POST['nombre'];
      
      if( $idioma->save() )
      {
         $this->new_message('Idioma creado correctamente.');
         header('Location: '.$idioma->url());
      }
      else
      {
         $this->new_error_msg('Error al guardar los datos.');
      }
      
      $this->share_extension();
   }
   
   private function editar_idioma()
   {
      $this->template = 'idioma_fac_det';
      
      $idi0 = new idioma_fac_det();
      $this->idioma = $idi0->get($_REQUEST['cod']);
      if($this->idioma)
      {
         if( isset($_POST['nombre']) )
         {
            $this->idioma->nombre = $_POST['nombre'];
            $this->idioma->activo = isset($_POST['activo']);
            
            $this->idioma->albaran = $_POST['albaran'];
            $this->idioma->cant = $_POST['cant'];
            $this->idioma->cliente = $_POST['cliente'];
            $this->idioma->descripcion = $_POST['descripcion'];
            $this->idioma->dto = $_POST['dto'];
            $this->idioma->email = $_POST['email'];
            $this->idioma->factura = $_POST['factura'];
            $this->idioma->fax = $_POST['fax'];
            $this->idioma->fecha = $_POST['fecha'];
            $this->idioma->forma_pago = $_POST['forma_pago'];
            $this->idioma->importe = $_POST['importe'];
            $this->idioma->importes = $_POST['importes'];
            $this->idioma->irpf = $_POST['irpf'];
            $this->idioma->iva = $_POST['iva'];
            $this->idioma->neto = $_POST['neto'];
            $this->idioma->num_cliente = $_POST['num_cliente'];
            $this->idioma->observaciones = $_POST['observaciones'];
            $this->idioma->pagina = $_POST['pagina'];
            $this->idioma->pedido = $_POST['pedido'];
            $this->idioma->precio = $_POST['precio'];
            $this->idioma->rec_equiv = $_POST['rec_equiv'];
            $this->idioma->suma_sigue = $_POST['suma_sigue'];
            $this->idioma->telefono = $_POST['telefono'];
            $this->idioma->total = $_POST['total'];
            $this->idioma->vencimiento = $_POST['vencimiento'];
            $this->idioma->web = $_POST['web'];
            
            if( $this->idioma->save() )
            {
               $this->new_message('Datos guardados correctamente.');
            }
            else
            {
               $this->new_error_msg('Error al guardar los datos.');
            }
         }
         
         $this->share_extension();
      }
      else
      {
         $this->new_error_msg('Idioma no encontrado.');
      }
   }
   
   private function eliminar_idioma()
   {
      $idioma = $this->idioma->get($_GET['delete_idioma']);
      if($idioma)
      {
         /// primero desactivamos
         $idioma->activo = FALSE;
         if( $idioma->save() )
         {
            /// de esta forma share_extension elimina las extensiones
            $this->share_extension();
         }
         
         /// ahora eliminamos
         if( $idioma->delete() )
         {
            $this->new_message('Idioma eliminado correctamente.');
         }
         else
         {
            $this->new_error_msg('Error al eliminar el idioma.');
         }
      }
      else
      {
         $this->new_error_msg('Idioma no encontrado.');
      }
   }
}
