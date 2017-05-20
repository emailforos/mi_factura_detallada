<?php

/*
 * This file is part of FacturaSctipts
 * Copyright (C) 2016  Javier Trujillo Jimenez  javier.trujillo.jimenez@gmail.com
 * Copyright (C) 2017  Carlos García Gómez      neorazorx@gmail.com
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

/**
 * Description of idioma_fac_det
 *
 * @author Carlos García Gómez
 */
class idioma_fac_det extends fs_model
{
   public $codidioma;
   public $nombre;
   public $activo;
   
   /// traducciones
   public $telefono;
   public $fax;
   public $email;
   public $web;
   public $factura;
   public $albaran;
   public $pedido;
   public $pagina;
   public $fecha;
   public $num_cliente;
   public $cliente;
   public $forma_pago;
   public $vencimiento;
   public $descripcion;
   public $cant;
   public $precio;
   public $dto;
   public $iva;
   public $importe;
   public $importes;
   public $neto;
   public $rec_equiv;
   public $irpf;
   public $total;
   public $suma_sigue;
   public $observaciones;
   
   public function __construct($e = FALSE)
   {
      parent::__construct('idiomas_fac_det');
      if($e)
      {
         $this->codidioma = $e['codidioma'];
         $this->nombre = $e['nombre'];
         $this->activo = $this->str2bool($e['activo']);
         
         $this->telefono = $e['telefono'];
         $this->fax = $e['fax'];
         $this->email = $e['email'];
         $this->web = $e['web'];
         $this->factura = $e['factura'];
         $this->albaran = $e['albaran'];
         $this->pedido = $e['pedido'];
         $this->pagina = $e['pagina'];
         $this->fecha = $e['fecha'];
         $this->num_cliente = $e['num_cliente'];
         $this->cliente = $e['cliente'];
         $this->forma_pago = $e['forma_pago'];
         $this->vencimiento = $e['vencimiento'];
         $this->descripcion = $e['descripcion'];
         $this->cant = $e['cant'];
         $this->precio = $e['precio'];
         $this->dto = $e['dto'];
         $this->iva = $e['iva'];
         $this->importe = $e['importe'];
         $this->importes = $e['importes'];
         $this->neto = $e['neto'];
         $this->rec_equiv = $e['rec_equiv'];
         $this->irpf = $e['irpf'];
         $this->total = $e['total'];
         $this->suma_sigue = $e['suma_sigue'];
         $this->observaciones = $e['observaciones'];
      }
      else
      {
         $this->codidioma = NULL;
         $this->nombre = NULL;
         $this->activo = TRUE;
         
         $this->telefono = 'teléfono';
         $this->fax = 'fax';
         $this->email = 'email';
         $this->web = 'web';
         $this->factura = 'factura';
         $this->albaran = 'albarán';
         $this->pedido = 'pedido';
         $this->pagina = 'página';
         $this->fecha = 'fecha';
         $this->num_cliente = 'nº de cliente';
         $this->cliente = 'cliente';
         $this->forma_pago = 'forma de pago';
         $this->vencimiento = 'vencimiento';
         $this->descripcion = 'descripción';
         $this->cant = 'cant';
         $this->precio = 'precio';
         $this->dto = 'dto';
         $this->iva = 'iva';
         $this->importe = 'importe';
         $this->importes = 'importes';
         $this->neto = 'neto';
         $this->rec_equiv = 'rec. equiv.';
         $this->irpf = 'irpf';
         $this->total = 'total';
         $this->suma_sigue = 'suma y sigue';
         $this->observaciones = 'observaciones';
      }
   }
   
   protected function install()
   {
      return "INSERT INTO idiomas_fac_det (codidioma,nombre,activo) VALUES ('es_ES','Español',TRUE);";
   }
   
   public function url()
   {
      return 'index.php?page=opciones_factura_detallada&cod='.$this->codidioma;
   }
   
   public function get($codidioma)
   {
      $data = $this->db->select("SELECT * FROM idiomas_fac_det WHERE codidioma = ".$this->var2str($codidioma).";");
      if($data)
      {
         return new idioma_fac_det($data[0]);
      }
      else
         return FALSE;
   }
   
   
   public function exists()
   {
      if( is_null($this->codidioma) )
      {
         return FALSE;
      }
      else
         return $this->db->select("SELECT * FROM idiomas_fac_det WHERE codidioma = ".$this->var2str($this->codidioma).";");
   }
   
   public function save()
   {
      $this->codidioma = $this->no_html($this->codidioma);
      $this->nombre = $this->no_html($this->nombre);
      
      $this->albaran = $this->no_html($this->albaran);
      $this->cant = $this->no_html($this->cant);
      $this->cliente = $this->no_html($this->cliente);
      $this->descripcion = $this->no_html($this->descripcion);
      $this->dto = $this->no_html($this->dto);
      $this->email = $this->no_html($this->email);
      $this->factura = $this->no_html($this->factura);
      $this->fax = $this->no_html($this->fax);
      $this->fecha = $this->no_html($this->fecha);
      $this->forma_pago = $this->no_html($this->forma_pago);
      $this->importe = $this->no_html($this->importe);
      $this->importes = $this->no_html($this->importes);
      $this->irpf = $this->no_html($this->irpf);
      $this->iva = $this->no_html($this->iva);
      $this->neto = $this->no_html($this->neto);
      $this->num_cliente = $this->no_html($this->num_cliente);
      $this->observaciones = $this->no_html($this->observaciones);
      $this->pagina = $this->no_html($this->pagina);
      $this->pedido = $this->no_html($this->pedido);
      $this->precio = $this->no_html($this->precio);
      $this->rec_equiv = $this->no_html($this->rec_equiv);
      $this->suma_sigue = $this->no_html($this->suma_sigue);
      $this->telefono = $this->no_html($this->telefono);
      $this->total = $this->no_html($this->total);
      $this->vencimiento = $this->no_html($this->vencimiento);
      $this->web = $this->no_html($this->web);
      
      if( $this->exists() )
      {
         $sql = "UPDATE idiomas_fac_det SET nombre = ".$this->var2str($this->nombre)
                 .", activo = ".$this->var2str($this->activo)
                 .", albaran = ".$this->var2str($this->albaran)
                 .", cant = ".$this->var2str($this->cant)
                 .", cliente = ".$this->var2str($this->cliente)
                 .", descripcion = ".$this->var2str($this->descripcion)
                 .", dto = ".$this->var2str($this->dto)
                 .", email = ".$this->var2str($this->email)
                 .", factura = ".$this->var2str($this->factura)
                 .", fax = ".$this->var2str($this->fax)
                 .", fecha = ".$this->var2str($this->fecha)
                 .", forma_pago = ".$this->var2str($this->forma_pago)
                 .", importe = ".$this->var2str($this->importe)
                 .", importes = ".$this->var2str($this->importes)
                 .", irpf = ".$this->var2str($this->irpf)
                 .", iva = ".$this->var2str($this->iva)
                 .", neto = ".$this->var2str($this->neto)
                 .", num_cliente = ".$this->var2str($this->num_cliente)
                 .", observaciones = ".$this->var2str($this->observaciones)
                 .", pagina = ".$this->var2str($this->pagina)
                 .", pedido = ".$this->var2str($this->pedido)
                 .", precio = ".$this->var2str($this->precio)
                 .", rec_equiv = ".$this->var2str($this->rec_equiv)
                 .", suma_sigue = ".$this->var2str($this->suma_sigue)
                 .", telefono = ".$this->var2str($this->telefono)
                 .", total = ".$this->var2str($this->total)
                 .", vencimiento = ".$this->var2str($this->vencimiento)
                 .", web = ".$this->var2str($this->web)
                 ."  WHERE codidioma = ".$this->var2str($this->codidioma).";";
      }
      else
      {
         $sql = "INSERT INTO idiomas_fac_det (codidioma,activo,albaran,nombre,cant,cliente"
                 . ",descripcion,dto,email,factura,fax,fecha,forma_pago,importe,importes,irpf,iva"
                 . ",neto,num_cliente,observaciones,pagina,pedido,precio,rec_equiv,suma_sigue,telefono"
                 . ",total,vencimiento,web) VALUES "
                 . "(".$this->var2str($this->codidioma)
                 . ",".$this->var2str($this->activo)
                 . ",".$this->var2str($this->albaran)
                 . ",".$this->var2str($this->nombre)
                 . ",".$this->var2str($this->cant)
                 . ",".$this->var2str($this->cliente)
                 . ",".$this->var2str($this->descripcion)
                 . ",".$this->var2str($this->dto)
                 . ",".$this->var2str($this->email)
                 . ",".$this->var2str($this->factura)
                 . ",".$this->var2str($this->fax)
                 . ",".$this->var2str($this->fecha)
                 . ",".$this->var2str($this->forma_pago)
                 . ",".$this->var2str($this->importe)
                 . ",".$this->var2str($this->importes)
                 . ",".$this->var2str($this->irpf)
                 . ",".$this->var2str($this->iva)
                 . ",".$this->var2str($this->neto)
                 . ",".$this->var2str($this->num_cliente)
                 . ",".$this->var2str($this->observaciones)
                 . ",".$this->var2str($this->pagina)
                 . ",".$this->var2str($this->pedido)
                 . ",".$this->var2str($this->precio)
                 . ",".$this->var2str($this->rec_equiv)
                 . ",".$this->var2str($this->suma_sigue)
                 . ",".$this->var2str($this->telefono)
                 . ",".$this->var2str($this->total)
                 . ",".$this->var2str($this->vencimiento)
                 . ",".$this->var2str($this->web).");";
      }
      
      return $this->db->exec($sql);
   }
   
   public function delete()
   {
      return $this->db->exec("DELETE FROM idiomas_fac_det WHERE codidioma = ".$this->var2str($this->codidioma).";");
   }
   
   public function all()
   {
      $elist = array();
      
      $data = $this->db->select("SELECT * FROM idiomas_fac_det ORDER BY codidioma ASC;");
      if($data)
      {
         foreach($data as $d)
         {
            $elist[] = new idioma_fac_det($d);
         }
      }
      
      return $elist;
   }
   
   public function fix_html($txt)
   {
      $newt = str_replace('&lt;', '<', $txt);
      $newt = str_replace('&gt;', '>', $newt);
      $newt = str_replace('&quot;', '"', $newt);
      $newt = str_replace('&#39;', "'", $newt);
      $newt = str_replace('&#8211;', '-', $newt);
      $newt = str_replace('&#8212;', '-', $newt);
      $newt = str_replace('&#8213;', '-', $newt);
      $newt = str_replace('–', '-', $newt);
      return $newt;
   }
}
