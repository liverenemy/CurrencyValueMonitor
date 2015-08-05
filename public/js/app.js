/**
 * The app script
 */

var app = angular.module('currency', []);

app.controller('CurrencyController', ['$http', '$scope', function($http, $scope){
    var context = this;
    this.title = 'Currencies';
    context.currencies = [];
    context.currencyIndex = {};
    context.selectedProviderId = 0;
    context.providers = [];
    context.baseCurrencyId = 0;
    context.values = [];
    $http
        .get('/currency/list')
        .success(function(data) {
            context.currencies = data;
            if ((data instanceof Array) && (data.length != undefined)) {
                for (var i = 0; i < data.length; i++) {
                    if (data[i].id == undefined) {
                        continue;
                    }
                    var id = data[i].id;
                    context.currencyIndex[id] = data[i];
                }
            }
        })
    ;
    $http
        .get('/provider/list')
        .success(function(data) {
            context.providers = data;
        })
    ;

    /**
     * Check whether the currency with the specified abbr may be selected as base currency
     * @param {Number} id
     * @returns {*}
     */
    context.acceptsAsBase = function(id) {
        var currency = context.currencyIndex[id];
        if (currency == undefined) {
            console.log('fail: ' + id);
            return false;
        }
        return (!!currency.isSupported && currency.isActive);
    };

    /**
     * Check whether values can be requested
     *
     * @returns {boolean}
     */
    context.canGetValues = function() {
        return context.hasSelectedBaseValue() && context.hasSelectedProvider();
    };

    context.change = function(currency) {
        var id = currency.id,
            isActive = currency.isActive ? 1 : 0
            ;
        jQuery.ajax('/currency/activate', {
            data: {
                'id': id,
                'isActive': isActive
            },
            success: function(data) {
                $scope.$apply(function(){
                    // Specially for AngularJS
                    currency.isActive = !!currency.isActive;
                });
            }
        });
    };

    /**
     * Get the provider with the specified Id
     * @param {Number} providerId
     * @returns {*}
     */
    context.getProvider = function(providerId) {
        var providers = context.providers;
        if (providers == undefined || providers.length == undefined) {
            return null;
        }
        for (var i = 0; i < providers.length; i++) {
            if (providers[i].id == providerId) {
                return providers[i];
            }
        }
    };

    /**
     * Get currency values
     */
    context.getValues = function() {
        var
            currency = context.baseCurrencyId,
            provider = context.selectedProviderId
            ;
        jQuery.ajax('/provider/one', {
            data: {
                'provider': provider,
                'baseCurrency': currency
            },
            success: function(data) {
                $scope.$apply(function(){
                    context.values = data;
                });
            }
        });
    };

    /**
     * Check whether the base currency was selected
     * @returns {boolean}
     */
    context.hasSelectedBaseValue = function() {
        return (context.baseCurrencyId != undefined && context.baseCurrencyId > 0);
    };

    /**
     * Check whether the provider was selected
     * @returns {boolean}
     */
    context.hasSelectedProvider = function() {
        return (context.selectedProviderId != undefined && context.selectedProviderId > 0);
    };

    /**
     * Update an index of the currencies supported by the selected provider
     */
    context.indexSupportedCurrencies = function() {
        var providerId = context.selectedProviderId,
            provider = context.getProvider(providerId),
            supportedCurrencyIds = provider.supportedCurrencyIds
        ;
        if (supportedCurrencyIds == undefined ||
            supportedCurrencyIds.length == undefined ||
            context.currencyIndex == undefined
        ) {
            return;
        }
        for (var i in context.currencyIndex) {
            var id = context.currencyIndex[i].id.toString();
            context.currencyIndex[i].isSupported = (supportedCurrencyIds.indexOf(id) > -1);
        }
    };

    context.showResults = function() {
        return context.values.length > 0;
    };
}]);