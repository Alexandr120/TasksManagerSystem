import './bootstrap';

import { Client } from './client';
window.client = Client();

import { Main } from "./main";
window.main = Main();

import { Auth } from "./auth";
window.auth = Auth();

import { TasksList }  from "./tasks/index";
window.tasks = TasksList();

import { Task }  from "./tasks/edit";
window.task = Task();

import { TeamsList } from './teams/index';
window.teams = TeamsList();

import { Team } from './teams/edit';
window.team = Team();

import { Paginate } from "./paginate";
window.paginate = Paginate();

import { ActiveFilters } from "./active-filters";
window.activeFilters = ActiveFilters();
