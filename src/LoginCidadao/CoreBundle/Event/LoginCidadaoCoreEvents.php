<?php
/**
 * This file is part of the login-cidadao project or it's bundles.
 *
 * (c) Guilherme Donato <guilhermednt on github>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LoginCidadao\CoreBundle\Event;

class LoginCidadaoCoreEvents
{
    /**
     * Receives a GetClientEvent
     */
    const GET_CLIENT = 'login_cidadao.core.get_client';

    /**
     * This event gets triggered before redirecting the user to his/her final intent to check if there are pending tasks
     * This event receives a GetTasksEvent
     */
    const GET_TASKS = 'login_cidadao.core.get_tasks';
}
