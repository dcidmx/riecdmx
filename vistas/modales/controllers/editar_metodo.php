<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Edición de <?php echo $modelo[0][3]; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_content">
							<form role="form" id="edita_modelo">
								<div class="panel panel-primary">
									<div class="panel-body">
										<div class="row">
											<div class="col-md-12">
												  <div class="form-group">
													<label for="controlador">Controlador</label>
													<input id="controlador" name="controlador" type="text" placeholder="Controlador" class="form-control" value="<?php echo $modelo[0][1]; ?>">
												  </div>
												  <div class="form-group">
													<label for="metodo">Método</label>
													<input id="metodo" name="metodo" type="text" class="form-control" placeholder="Método" value="<?php echo $modelo[0][2]; ?>">
												  </div>
												  <div class="form-group">
													<label for="nombre">Nombre</label>
													<input id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" value="<?php echo $modelo[0][3]; ?>">
												  </div>
												  <div class="form-group">
													<label for="descripcion">Descripción</label>
													<textarea id="descripcion" name="descripcion" type="text" class="form-control"  placeholder="Descripción"><?php echo $modelo[0][4]; ?></textarea>
												  </div>
											</div>
										</div>
									</div>
								</div>
								<input id="id_metodo" name="id_metodo" type="hidden" value="<?php echo $modelo[0][0]; ?>">
							</form>
            </div>
						<div class="modal-footer">
              <button type="button" class="btn btn-primary" id="cntr_js_fn_02">Editar</button>
              <button type="button" class="btn btn-danger" id="cntr_js_fn_03">Eliminar Par</button>
              <button  data-dismiss="modal" class="btn btn-secondary" type="button">Cancelar</button>
						</div>
        </div>
    </div>
</div>
