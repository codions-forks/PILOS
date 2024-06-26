<template>
  <div>
    <h3>
      {{ id === 'new' ? $t('settings.server_pools.new') : (
        viewOnly ? $t('settings.server_pools.view', { name : model.name })
          : $t('settings.server_pools.edit', { name: model.name })
      ) }}
    </h3>
    <hr>

    <b-overlay :show="isBusy || modelLoadingError">
      <template #overlay>
        <div class="text-center">
          <b-spinner v-if="isBusy" ></b-spinner>
          <b-button
            v-else
            ref="reloadServerPool"
            @click="load()"
          >
            <i class="fa-solid fa-sync"></i> {{ $t('app.reload') }}
          </b-button>
        </div>
      </template>

      <b-form @submit='saveServerPool' :aria-hidden="modelLoadingError">
        <b-container fluid>
          <b-form-group
            label-cols-sm='4'
            :label="$t('app.model_name')"
            label-for='name'
            :state='fieldState("name")'
          >
            <b-form-input id='name' type='text' v-model='model.name' :state='fieldState("name")' :disabled='isBusy || modelLoadingError || viewOnly'></b-form-input>
            <template #invalid-feedback><div v-html="fieldError('name')"></div></template>
          </b-form-group>
          <b-form-group
            label-cols-sm='4'
            :label="$t('app.description')"
            label-for='description'
            :state='fieldState("description")'
          >
            <b-form-input id='description' type='text' v-model='model.description' :state='fieldState("description")' :disabled='isBusy || modelLoadingError || viewOnly'></b-form-input>
            <template #invalid-feedback><div v-html="fieldError('description')"></div></template>
          </b-form-group>
          <b-form-group
            label-cols-sm='4'
            :label="$t('app.servers')"
            label-for='servers'
            :state='fieldState("servers", true)'
          >
            <b-input-group>
              <multiselect
                :placeholder="$t('settings.server_pools.select_servers')"
                ref="servers-multiselect"
                v-model='model.servers'
                track-by='id'
                open-direction='bottom'
                :multiple='true'
                :searchable='false'
                :internal-search='false'
                :clear-on-select='false'
                :close-on-select='false'
                :show-no-results='false'
                :showLabels='false'
                :options='servers'
                :disabled="isBusy || modelLoadingError || serversLoadingError || viewOnly"
                id='servers'
                :loading='serversLoading'
                :allowEmpty='true'
                :class="{ 'is-invalid': fieldState('servers', true), 'multiselect-form-control': true }">
                <template #noOptions>{{ $t('settings.servers.no_data') }}</template>
                <template v-slot:option="{ option }">{{ option.name }}</template>
                <template v-slot:tag="{ option, remove }">
                  <h5 class='d-inline mr-1 mb-1'>
                    <b-badge variant='secondary'>
                      {{ option.name }}
                      <span @click='remove(option)'><i class="fa-solid fa-xmark" :aria-label="$t('settings.server_pools.remove_server')"></i></span>
                    </b-badge>
                  </h5>
                </template>
                <template #afterList>
                  <b-button
                    :disabled='serversLoading || currentPage === 1'
                    variant='outline-secondary'
                    @click='loadServers(Math.max(1, currentPage - 1))'>
                    <i class='fa-solid fa-arrow-left'></i> {{ $t('app.previous_page') }}
                  </b-button>
                  <b-button
                    :disabled='serversLoading || !hasNextPage'
                    variant='outline-secondary'
                    @click='loadServers(currentPage + 1)'>
                    <i class='fa-solid fa-arrow-right'></i> {{ $t('app.next_page') }}
                  </b-button>
                </template>
              </multiselect>
              <b-input-group-append>
                <b-button
                  v-if="serversLoadingError"
                  @click="loadServers(currentPage)"
                  variant="outline-secondary"
                ><i class="fa-solid fa-sync"></i></b-button>
              </b-input-group-append>
            </b-input-group>
            <template #invalid-feedback><div v-html="fieldError('servers', true)"></div></template>
          </b-form-group>

          <hr>
          <b-row class='my-1 float-right'>
            <b-col sm='12'>
              <b-button
                :disabled='isBusy'
                variant='secondary'
                @click="$router.push({ name: 'settings.server_pools' })">
                <i class='fa-solid fa-arrow-left'></i> {{ $t('app.back') }}
              </b-button>
              <b-button
                :disabled='isBusy || modelLoadingError || serversLoadingError || serversLoading'
                variant='success'
                type='submit'
                class='ml-1'
                v-if='!viewOnly'>
                <i class='fa-solid fa-save'></i> {{ $t('app.save') }}
              </b-button>
            </b-col>
          </b-row>

        </b-container>

      </b-form>

      <b-modal
        :static='modalStatic'
        :busy='isBusy'
        ok-variant='danger'
        cancel-variant='secondary'
        @ok='forceOverwrite'
        @cancel='refreshServerPool'
        :hide-header-close='true'
        :no-close-on-backdrop='true'
        :no-close-on-esc='true'
        ref='stale-server-pool-modal'
        :hide-header='true'>
        <template v-slot:default>
          <h5>{{ staleError.message }}</h5>
        </template>
        <template v-slot:modal-ok>
          <b-spinner small v-if="isBusy"></b-spinner>  {{ $t('app.overwrite') }}
        </template>
        <template v-slot:modal-cancel>
          <b-spinner small v-if="isBusy"></b-spinner>  {{ $t('app.reload') }}
        </template>
      </b-modal>
    </b-overlay>
  </div>
</template>

<script>
import Base from '../../../api/base';
import FieldErrors from '../../../mixins/FieldErrors';
import env from '../../../env';

import { Multiselect } from 'vue-multiselect';
import _ from 'lodash';

export default {
  components: { Multiselect },

  mixins: [FieldErrors],
  props: {
    id: {
      type: [String, Number],
      required: true
    },

    viewOnly: {
      type: Boolean,
      required: true
    },

    modalStatic: {
      type: Boolean,
      default: false
    }
  },

  data () {
    return {
      model: {
        servers: []
      },
      errors: {},
      staleError: {},
      isBusy: false,
      modelLoadingError: false,

      serversLoading: false,
      servers: [],
      currentPage: 1,
      hasNextPage: false,
      serversLoadingError: false
    };
  },

  /**
   * Loads the server from the backend
   */
  mounted () {
    this.load();

    this.loadServers();
  },

  methods: {

    /**
     * Loads the server pool from the backend
     */
    load () {
      this.modelLoadingError = false;

      if (this.id !== 'new') {
        this.isBusy = true;

        Base.call(`serverPools/${this.id}`).then(response => {
          this.model = response.data.data;
        }).catch(error => {
          if (error.response && error.response.status === env.HTTP_NOT_FOUND) {
            this.$router.push({ name: 'settings.server_pools' });
          } else {
            this.modelLoadingError = true;
          }
          Base.error(error, this.$root, error.message);
        }).finally(() => {
          this.isBusy = false;
        });
      }
    },

    /**
     * Loads the roles for the passed page, that can be selected through the multiselect.
     *
     * @param [page=1] The page to load the roles for.
     */
    loadServers (page = 1) {
      this.serversLoading = true;

      const config = {
        params: {
          page
        }
      };

      Base.call('servers', config).then(response => {
        this.serversLoadingError = false;
        this.servers = response.data.data;
        this.currentPage = page;
        this.hasNextPage = page < response.data.meta.last_page;
      }).catch(error => {
        this.$refs['servers-multiselect'].deactivate();
        this.serversLoadingError = true;
        Base.error(error, this.$root, error.message);
      }).finally(() => {
        this.serversLoading = false;
      });
    },

    /**
     * Saves the changes of the server to the database by making a api call.
     *
     * @param evt
     */
    saveServerPool (evt) {
      if (evt) {
        evt.preventDefault();
      }

      this.isBusy = true;

      const config = {
        method: this.id === 'new' ? 'post' : 'put',
        data: _.cloneDeep(this.model)
      };

      config.data.servers = config.data.servers.map(server => server.id);

      Base.call(this.id === 'new' ? 'serverPools' : `serverPools/${this.id}`, config).then(() => {
        this.$router.push({ name: 'settings.server_pools' });
      }).catch(error => {
        if (error.response && error.response.status === env.HTTP_UNPROCESSABLE_ENTITY) {
          this.errors = error.response.data.errors;
        } else if (error.response && error.response.status === env.HTTP_STALE_MODEL) {
          this.staleError = error.response.data;
          this.$refs['stale-server-pool-modal'].show();
        } else {
          if (error.response && error.response.status === env.HTTP_NOT_FOUND) {
            this.$router.push({ name: 'settings.server_pools' });
          }

          Base.error(error, this.$root, error.message);
        }
      }).finally(() => {
        this.isBusy = false;
      });
    },

    /**
     * Force a overwrite of the server in the database by setting the `updated_at` field to the new one.
     */
    forceOverwrite () {
      this.model.updated_at = this.staleError.new_model.updated_at;
      this.staleError = {};
      this.$refs['stale-server-pool-modal'].hide();
      this.saveServerPool();
    },

    /**
     * Refreshes the current model with the new passed from the stale error response.
     */
    refreshServerPool () {
      this.model = this.staleError.new_model;
      this.staleError = {};
      this.$refs['stale-server-pool-modal'].hide();
    }
  }
};
</script>

<style scoped>

</style>
