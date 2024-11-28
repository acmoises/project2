<nav>
    <?php
        $menuHierarchy = (new \Controllers\MenuController())->getHierarchicalMenu();
        $renderer = new \Model\MenuRenderer();
        $renderer->renderMenu($menuHierarchy);
    ?>
</nav>

<div class="description" id="menu-description">
    <p style="">Seleccione un elemento del menú para ver su descripción aquí.</p>
</div>

<table>
    <thead>

    <tr>
        <th colspan="5" class="title">
            <div>
                <h2>Gestión de Menús</h2>
                <a href="create" class="btn btn-add">Añadir Menú</a>
            </div>
        </th>
    </tr>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Menú Padre</th>
        <th>Descripción</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($menus)): ?>
        <?php foreach ($menus as $menu): ?>
            <tr>
                <td><?= htmlspecialchars($menu->id); ?></td>
                <td><?= htmlspecialchars($menu->name); ?></td>
                <td>
                    <?= $menu->parent_name
                        ? htmlspecialchars($menu->parent_name)
                        : 'N/A'; ?>
                </td>
                <td><?= htmlspecialchars($menu->description); ?></td>

                <td class="actions">
                    <a href="/project2/update?id=<?php echo $menu->id ?>" class="btn btn-edit">Editar</a>

                    <form method="POST" action="/project2/delete">
                        <input type="hidden" name="id" value="<?php echo $menu->id; ?>">
                        <input type="submit" value="Eliminar" class="btn btn-delete" onclick="return confirm('¿Estás seguro de que deseas eliminar este menú?')">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No hay menús disponibles.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
