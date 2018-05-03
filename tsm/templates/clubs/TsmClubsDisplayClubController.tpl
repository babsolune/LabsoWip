<section id="tsm-module">
    <header>
        <h1>{@tsm.module.title} - {@clubs.clubs}</h1>
        # IF C_MODERATE #
            <span class="actions">
                <a href="{U_CONFIG}" title="{@clubs.config}"><i class="fa fa-cog fa-fw"></i></a>
            </span>
        # ENDIF #
    </header>
    <article>
        <header>
            <h2># IF C_HAS_LOGO_MINI #<img src="" alt="{NAME}" /># ELSE #<img src="{DEFAULT_LOGO_MINI}" alt="{NAME}" /># ENDIF # {NAME}</h2>
            # IF C_MODERATE #
                <span class="actions">
                    # IF C_EDIT #<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fas fa-edit fa-fw"></i></a># ENDIF #
                    # IF C_DELETE #<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fas fa-trash fa-fw"></i></a># ENDIF #
                </span>
            # ENDIF #
        </header>
        <div class="content">
            Vue des détails d'un club
        </div>
        <footer></footer>
    </article>
    <footer></footer>
</section>
