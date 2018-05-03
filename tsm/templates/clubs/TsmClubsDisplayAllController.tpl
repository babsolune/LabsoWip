<section id="tsm-module">
    <header>
        <h1>
            {@tsm.module.title} - {@clubs.clubs}
            # IF C_MODERATE # <span class="actions"><a href="{U_CONFIG}" title="{@clubs.config}"><i class="fa fa-cog fa-fw"></i></a></span># ENDIF #
        </h1>
    </header>
    # IF C_TABLE #
        <table id="table">
            <thead>
                <tr>
                    <th></th>
                    <th>{@clubs.club}</th>
                    <th>{@clubs.details}</th>
                    <th>{@clubs.website}</th>
                    # IF C_MODERATE #<th></th># ENDIF #
                </tr>
            </thead>
            <tbody>
                # START clubs #
                    <tr>
                        <td>
                            # IF clubs.C_HAS_LOGO_MINI #
                                <img src="{clubs.U_LOGO_MINI}" class="club-logo-mini" alt="{clubs.CLUB_NAME}" />
                            # ELSE #
                                <img src="{clubs.DEFAULT_LOGO_MINI}" class="club-logo-mini" alt="{clubs.CLUB_NAME}" />
                            # ENDIF #
                        </td>
                        <td>{clubs.NAME}</td>
                        <td><a href="{clubs.U_CLUB}">{@clubs.details}</a></td>
                        <td>
                            # IF clubs.C_HAS_WEBSITE #
                                <a href="{clubs.U_WEBSITE}"# IF C_NEW_WINDOW # target="_blank" rel="noopener noreferrer"# ENDIF #>{@clubs.visit}</a>
                            # ELSE #-
                            # ENDIF #
                        </td>
                        # IF clubs.C_MODERATE #
                        <td>
                            # IF clubs.C_EDIT #<a href="{clubs.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fas fa-edit fa-fw"></i></a># ENDIF #
                            # IF clubs.C_DELETE #<a href="{clubs.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fas fa-trash fa-fw"></i></a># ENDIF #
                        </td>
                        # ENDIF #
                    </tr>
                # END clubs #
            </tbody>
        </table>
    # ELSE #
    <div class="elements-container columns-{COLS_NB}">
        # START clubs #
            <article class="# IF C_MOSAIC # block"# ENDIF #>
                Vue de la liste des clubs
            </article>

        # END clubs #
    </div>
    # ENDIF #
    <footer></footer>
</section>
