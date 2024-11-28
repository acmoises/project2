<div class="form">
    <div class="form-container">
        <h1><?= isset($menu) ? 'Editar Menú' : 'Crear Menú'; ?></h1>
        <form action="/project2/create" method="post">
            <div class="form-group">
                <label for="name">Nombre del Menú</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($menu->name ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="parent_id">Menú Padre</label>
                <select id="parent_id" name="parent_id">
                    <option value="0">Ninguno</option>
                    <?php foreach ($menus as $parent): ?>
                        <option value="<?= $parent->id; ?>" <?= isset($menu) && $menu->parent_id == $parent->id ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($parent->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea id="description" name="description"><?= htmlspecialchars($menu->description ?? ''); ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= isset($menu) ? 'Actualizar' : 'Crear'; ?></button>
                <a href="/project2" class="btn-cancel">Cancelar</a>
            </div>
        </form>
    </div>
</div>