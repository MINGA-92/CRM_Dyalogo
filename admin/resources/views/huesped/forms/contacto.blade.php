<table class="table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Telefono 1</th>
            <th>Telefono 2</th>
        </tr>
    </thead>
    <tbody>
        <tr class="linea-contacto">
            <td>
                <input type="text" class="form-control contac-nombre" name="contacto_nombre[]" placeholder="Ingresa el nombre" required form="form-huesped">
            </td>
            <td>
                <input type="email" class="form-control contac-email" name="contacto_email[]" placeholder="Ingresa el email" form="form-huesped">
            </td>
            <td>
                <select name="contacto_tipo[]" class="form-control" form="form-huesped">
                    <option value="T">Tecnico</option>
                    <option value="P">Pagos</option>
                    <option value="F">Funcional</option>
                </select>
            </td>
            <td>
                <input type="tel" class="form-control contac-telefono" name="contacto_telefono1[]" placeholder="Ingresa el numero de teléfono" form="form-huesped">
            </td>
            <td>
                <input type="tel" class="form-control" name="contacto_telefono2[]" placeholder="Ingresa el numero de teléfono" form="form-huesped">
            </td>
        </tr>
    </tbody>
</table>
