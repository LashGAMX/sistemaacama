<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- <link rel="stylesheet" href="'.asset('css/pdf/style.css').'"> --}}
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pdf/style.css')}}">

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"> --}}
    {{-- <link rel="stylesheet" href="'.asset('css/pdf/style.css').'" media="mpdf"> --}}

    <title>Cotizacion {{$model->Folio}}</title> 
</head>
<body> 
    <div class="container" id="pag">
        <div class="row">
            <div class="col align-self-end">
            <P align="right">FOLIO COTIZACIÓN: {{$model->Folio}}<p>
            </div>
        </div><br>
        <div class="row" style="display: block">
            <div class="col-12">
                {{$model->Nombre}}
            </div>
            <div class="col-12">
                {{$model->Direccion}}
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12">
                {{$model->Telefono}}<br>
                {{$model->Correo}}<br>
                {{$model->Atencion}}<br>
            </div><br>
            <div class="col-md-12">
                <p>ME PERMITO SOMETER A SU AMABLE CONSIDEREACIÓN LA SIGUIENTE COTIZACIÓN DEL SERVICIO DE MUESTREO Y ANÁLISIS DE AGUA DE ACEURDO A:</p>
            </div><br>
            <div class="col-md-12">
                <table class="table table-borderless" style="border:none">
                    <tr>
                        <td>SERVICIO: </td>
                        <td>{{$model->Servicio}}</td>
                        <td>NÚM NORMAS:</td>
                        <td>1</td>
                        <td>PUNTOS MUESTREO:</td>
                        <td>1</td>
                        <td>SERVICIOS:</td>
                        <td>1</td>
                    </tr>
                </table>
                <table class="table table-borderless" style="border:none">
                <tr>
                    <td>TIPO MUESTRA: </td>
                    <td>{{$model->Tipo_muestra}}</td>
                    <td>NORMA:</td>
                    <td>{{$model->Clave_norma}}</td>
                </tr>
            </table>
            </div><br>
            <div class="col-md-12">
                <strong><p>PRODUCTOS Y SERVICIOS. AGUA Y HIELO PARA CONSUMO HUMANO, ENVASADOS A GRANEL. ESPESIFICACIONES SANITARIAS</p></strong>
            </div>
        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <th>PARAMETRO</th>
                    <th>METODO DE PRUEBA</th>
                    <th><small>LIMITE DE CUANTIFICACIÓN DEL METODO</small></th>
                    <th>UNIDAD</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <table class="table">
            <tr>
                <td>CANTIDAD SERVICIOS: </td>
                <td>1.00</td>
                <td>COSTO UNITARIO</td>
                <td>{{$model->Sub_total}}</td>
                <td>COSTO SIN IVA</td>
                <td>{{$model->Sub_total}}</td>
            </tr>
        </table>
        <div class="col-md-12">
            <p>OBSERVACIONES COTIZACIÓN</p>
            <p style="font-weight: bold;">{{$model->Observacion_cotizacion}}</p>
        </div><br>
        <div class="col-md-12">
           <table class="table">
                <tr>
                <td>SUBTOTAL</td>
                <td>{{$model->Sub_total}}</td>
            </tr>
            <tr>
                <td>IVA</td>
                <td></td>
            </tr>
           </table>
        </div>
        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Expedita harum laborum animi cum dolore sunt distinctio cumque facilis! Porro magni facilis quia temporibus nostrum asperiores illum assumenda at. Ea suscipit labore, deleniti nostrum est aliquam? Perspiciatis voluptatum explicabo voluptatem nobis ipsa! Architecto excepturi impedit blanditiis itaque nemo dolorum ea placeat ex, id ipsa ducimus, enim similique ab maxime aliquam, sit illo eum sequi sunt. Adipisci reprehenderit iure error repellat impedit, iste porro aspernatur natus quisquam vero aut amet maxime tenetur corrupti magnam quod laborum ullam culpa? Facilis, impedit! Culpa, et! Alias nulla veniam et quas? Pariatur minus itaque incidunt explicabo tempore corrupti, ipsa illum voluptatibus nisi numquam deserunt eaque eius doloribus quisquam, praesentium debitis voluptas dolorum ad repudiandae consequatur tenetur eos hic? Id quas nemo deserunt consequuntur maxime, reiciendis provident inventore distinctio, explicabo similique nostrum placeat sit facere hic impedit sapiente rerum nisi, delectus quibusdam tempora asperiores voluptate ut. Magnam quod consectetur unde temporibus eius voluptate, laborum consequatur. Voluptates aliquid labore amet ratione iusto tenetur ex laudantium molestiae nulla praesentium ipsam reprehenderit autem, corporis ea exercitationem nam adipisci temporibus laborum mollitia consequuntur reiciendis doloribus aliquam nostrum sequi? Aut molestiae labore pariatur eveniet expedita, ipsam dolorem magni, sed iusto ut sunt impedit eum aliquam! Ipsa, sed voluptate dignissimos voluptatem eligendi non. Consequatur fugit reiciendis iure, in est accusamus et ipsa ratione voluptatum perferendis amet vel odit ex libero numquam eum esse minus reprehenderit soluta animi minima laborum. Possimus voluptate commodi impedit minus, nisi dolore tempore eius doloremque est adipisci! Sequi, laudantium omnis, aliquid earum sapiente dolor voluptatibus, pariatur asperiores dignissimos aspernatur atque culpa! Quasi, modi fuga quaerat ullam provident ad iusto magni dolorem atque quisquam ipsam beatae, temporibus tempora, vero maiores doloribus nostrum soluta quo recusandae architecto voluptate ab quod eos. Vero illo, fuga veniam, sed nulla placeat accusamus doloremque consequuntur repellendus quidem architecto libero beatae. Rem hic, sunt, quo nesciunt dicta blanditiis beatae deleniti minima necessitatibus, ut dolorum dolore doloremque. Fuga itaque doloribus sint suscipit, soluta sapiente! Qui asperiores nam harum fugiat nostrum perferendis? Quae saepe necessitatibus et! Ex quos accusantium quae inventore voluptate illum, unde quaerat commodi. Iusto, quas tempore. Possimus, modi, quia dignissimos placeat nam beatae laborum alias incidunt dicta sapiente, laboriosam voluptatum et tenetur quasi velit? Incidunt laborum ullam animi reprehenderit consequuntur eius nemo, totam voluptates assumenda eos omnis hic nihil est suscipit quae veniam consectetur ducimus qui asperiores inventore libero, modi sunt quod cupiditate? Odit blanditiis, totam voluptas excepturi id rerum. Reprehenderit quam, impedit voluptatibus, itaque consequatur iusto repellat facere in dolore amet, dolorum nam saepe quos voluptatum laboriosam ipsa neque harum? Tempore animi officia provident debitis fuga laboriosam, aliquam ipsa cumque pariatur dignissimos architecto hic doloremque illo dolor inventore, facere alias, nostrum voluptatem. Voluptates delectus excepturi impedit voluptatem minima quo dolore, ipsam, omnis totam ea possimus laboriosam quae nobis laudantium velit tempora repudiandae soluta fugiat temporibus at. A sapiente saepe reiciendis earum delectus, unde exercitationem suscipit odit eaque quo blanditiis, inventore nulla illum, sunt in labore dolorum officiis deleniti accusamus eius eum. Repudiandae nobis nulla illum quasi, labore debitis cum mollitia incidunt culpa reiciendis asperiores, ipsam vitae iste voluptatibus nostrum libero. Eveniet numquam blanditiis quas veritatis nulla dolor quod, commodi deserunt. Excepturi aut maiores architecto, soluta ad doloribus sapiente corporis maxime, voluptatem voluptate assumenda suscipit quas cupiditate debitis ducimus nobis totam laudantium pariatur! Quo et dolorem ullam porro eveniet! Consequuntur, dolorum a eaque, velit porro odit animi exercitationem ducimus fuga esse odio, eius provident ullam unde fugit voluptatibus neque sequi. Aspernatur ullam veniam ratione repellendus. Saepe officia commodi exercitationem animi facere ea culpa ratione libero. Laboriosam distinctio sit odio inventore temporibus. Optio alias incidunt ea, tempore iste delectus aspernatur, quibusdam eaque totam blanditiis officiis saepe tenetur commodi culpa, eius repudiandae in excepturi minima aliquam voluptates quis nemo. Voluptates omnis debitis, deleniti delectus harum quae corrupti. Rem nostrum possimus quis eos iure voluptatum voluptas maxime eaque optio minima voluptate repellat architecto ratione dicta in quibusdam ipsa placeat deserunt, quidem ipsam porro accusamus id sapiente. Magni minus ullam expedita facilis itaque ipsam aspernatur sequi veniam nobis ipsa quas deserunt, perspiciatis illo doloribus in. Dolores unde quis reiciendis quam, nemo inventore sit error? Cum, consequuntur tempore. Velit sint culpa fugiat recusandae, quidem in excepturi quis ducimus omnis sit asperiores tempora maiores praesentium beatae commodi blanditiis iure possimus, consequatur, sunt totam autem veritatis. Necessitatibus corporis nemo amet quos adipisci sit nisi tenetur! Reprehenderit est beatae iusto deserunt reiciendis recusandae fuga molestiae ab aspernatur labore cumque quibusdam quae, vitae sunt debitis veritatis minima asperiores praesentium itaque nihil ipsa! Officia corrupti a maxime, assumenda vitae obcaecati. Rerum unde eum dolor culpa iusto est temporibus placeat recusandae vel reprehenderit sit provident quidem impedit tempora sunt, voluptatibus saepe ipsa quae itaque veritatis ratione non totam! Saepe praesentium repellendus blanditiis iste necessitatibus quis laudantium quos provident consectetur commodi. Reprehenderit ipsa beatae aliquid sequi distinctio corporis sint veritatis numquam a aperiam assumenda eveniet doloremque voluptatum, ab nam ex, ut cum tenetur recusandae sit quo quisquam culpa voluptates neque. Excepturi dolores modi voluptas ipsa unde officia tempore harum quis eaque facere exercitationem fugiat dicta, recusandae esse quas nobis repellendus doloribus dolorum quisquam qui velit quam fuga. Deleniti at facilis neque sunt ratione cum vel provident, quia repellendus beatae sapiente quo architecto distinctio earum atque, ipsum rerum officiis enim rem exercitationem nihil excepturi velit optio porro? Distinctio hic voluptas quos quasi deleniti eveniet ab nesciunt error quis accusamus dolores laboriosam, laudantium omnis pariatur eos repellendus commodi! Aliquid qui natus suscipit quam, omnis est vitae tempore eveniet, debitis quae reiciendis quo repudiandae quidem corporis rerum cumque dolorem eius libero maxime. Modi voluptatibus, harum blanditiis, saepe, porro iure nobis distinctio qui cupiditate iste nisi? Eos quidem a atque voluptates illo sequi modi eaque soluta, possimus accusamus, itaque minus? Qui porro voluptas laudantium libero suscipit fugiat neque repudiandae sint. Neque fugiat accusamus delectus quaerat reprehenderit iste, illo ad sunt? Molestiae, velit autem accusamus cumque soluta sit! Mollitia culpa doloremque eius sed quaerat tempora, assumenda ad explicabo qui a similique, ea cum aut quia! Porro quibusdam autem, iusto amet libero nam.
    </div>
</body>
</html> 