<div data-control="toolbar">
    <a
        href="<?= Backend::url('moonwalkerz/trustpilot/reviews/create') ?>"
        class="btn btn-primary oc-icon-plus">
        <?= e(trans('backend::lang.form.create')) ?>
    </a>
    <button
        class="btn btn-default oc-icon-trash-o"
        data-request="onDelete"
        data-request-confirm="<?= e(trans('backend::lang.list.delete_selected_confirm')) ?>"
        data-list-checked-trigger
        data-list-checked-request
        data-stripe-load-indicator>
        <?= e(trans('backend::lang.list.delete_selected')) ?>
    </button>
    <button class="btn btn-danger" type="button" data-request="onSync">
    <?= e(trans('moonwalkerz.trustpilot::lang.settings.sync_button')) ?>
    </button>
</div>
