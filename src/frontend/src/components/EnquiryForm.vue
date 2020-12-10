<template>
  <v-main>
    <v-form ref="form">
      <v-container fluid>
        <v-row>
          <v-col
              cols="12"
          >
            <v-text-field
                v-model="fullName"
                :rules="fullNameRules"
                label="Full name"
                required
            ></v-text-field>
          </v-col>

          <v-col
              cols="12"
          >
            <v-text-field
                v-model="phone"
                :rules="phoneRules"
                label="Phone"
                required
            ></v-text-field>
          </v-col>

          <v-col
              cols="12"
          >
            <v-dialog
                ref="dialog"
                v-model="dateOfArrivalModal"
                :return-value.sync="dateOfArrival"
                persistent
                width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                    v-model="dateOfArrival"
                    label="Date of arrival"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    :rules="dateOfArrivalRules"
                ></v-text-field>
              </template>
              <v-date-picker
                  v-model="dateOfArrival"
                  :allowed-dates="allowedDatesOfArrival"
                  scrollable
              >
                <v-spacer></v-spacer>
                <v-btn
                    text
                    color="primary"
                    @click="dateOfArrivalModal = false"
                >
                  Cancel
                </v-btn>
                <v-btn
                    text
                    color="primary"
                    @click="$refs.dialog.save(dateOfArrival)"
                >
                  OK
                </v-btn>
              </v-date-picker>
            </v-dialog>
          </v-col>

          <v-col
              cols="12"
          >
            <v-text-field
                v-model="airFlightNumber"
                :rules="airFlightNumberRules"
                label="Air flight number"
                required
            ></v-text-field>
          </v-col>

          <v-col
              cols="12"
          >
            <v-select
                v-model="airport"
                :items="airportsChoices"
                :rules="airportRules"
                label="Airport"
            />
          </v-col>

          <v-col
              v-if="terminalsChoices.length > 0"
              cols="12"
          >
            <v-select
                v-model="terminal"
                :items="terminalsChoices"
                label="Terminal"
                :rules="terminalRules"
            />
          </v-col>
        </v-row>
      </v-container>
      <v-footer fixed>
        <v-container fluid>
          <v-btn ref="submit" color="light-blue darken-4" dark v-on:click="submit">Submit</v-btn>
        </v-container>
      </v-footer>
    </v-form>
    <ConfirmDialog v-model="confirmDialog"/>
  </v-main>
</template>

<script>
import backend from '@/api/backend';
import ConfirmDialog from '@/components/ConfirmDialog';

export default {
  name: 'EnquiryForm',
  components: {
    ConfirmDialog,
  },
  data: () => ({
    confirmDialog: false,
    fullName: '',
    fullNameRules: [
      v => !!v || 'Full name is required',
      v => /^[a-z,'-]+(\s)[a-z,'-]+$/i.test(v) || 'Full name must be valid',
    ],
    phone: '',
    phoneRules: [
      v => !!v || 'Phone is required',
      v => /^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/.test(v) || 'Phone must be valid',
    ],
    dateOfArrival: '',
    dateOfArrivalModal: false,
    dateOfArrivalRules: [
      v => !!v || 'Date of arrival is required',
      v => {
        if (Date.parse(v) >= new Date().setHours(0, 0, 0, 0)) {
          return true;
        }

        return 'Date of arrival must be greater or equal today';
      },
    ],
    airFlightNumber: null,
    airFlightNumberRules: [
      v => !!v || 'Air flight number is required',
      v => /^([a-z][a-z]|[a-z][0-9]|[0-9][a-z])[a-z]?[0-9]{1,4}[a-z]?$/i.test(v) || 'Air flight number must be valid',
    ],
    airports: [
      {
        name: 'Heathrow',
        code: 'heathrow',
        terminals: ['1', '2', '3', '4', 'not sure'],
      },
      {
        name: 'Gatwick',
        code: 'gatwick',
      },
    ],
    airport: null,
    airportRules: [
      v => !!v || 'Airport is required',
    ],
    terminal: null,
    terminalRules: [
      v => !!v || 'Terminal is required',
    ],
  }),
  methods: {
    allowedDatesOfArrival(v) {
      return Date.parse(v) >= new Date().setHours(0, 0, 0, 0);
    },
    submit() {
      if (!this.$refs.form.validate()) {
        return;
      }

      this.$refs.submit.loading = true;

      backend.enquirySave({
        fullName: this.fullName,
        phone: this.phone,
        dateOfArrival: this.dateOfArrival,
        airFlightNumber: this.airFlightNumber,
        airport: this.airport,
        terminal: this.terminal,
      }).then(() => {
        this.confirmDialog = true;
        this.clear();
      }).catch((error) => {
        if (error.response && error.response.data) {
          console.log(error.response.data)
        }
      }).finally(() => {
        this.$refs.submit.loading = false;
      });
    },
    clear() {
      this.fullName = null;
      this.phone = null;
      this.dateOfArrival = null;
      this.airFlightNumber = null;
      this.airport = null;
      this.terminal = null;
      this.$refs.form.resetValidation();
    },
  },
  watch: {
    airport() {
      this.terminal = null;
    }
  },
  computed: {
    airportsChoices() {
      return this.airports.map((airport) => {
        return {
          text: airport.name,
          value: airport.code,
        }
      })
    },
    terminalsChoices() {
      if (null === this.airport) {
        return [];
      }

      return this.airports.filter((airport) => {
        return this.airport === airport.code;
      })[0].terminals || [];
    }
  },
};
</script>