import {watch, ref, toRaw} from 'vue';
import {ElMessage} from "element-plus";

type ValueType = {
    name: string, _index: number | string,
}

export type SkuItem = {
    price: number | ''; stock: number | ''; _index: (string | number)[],
}

export type GroupType = {
    name: string, _index: number | string, children: ValueType []
}

export function Attr() {
    const specGroups = ref<GroupType[]>([]);
    const skus = ref<SkuItem[]>([]);

    function cartesianProduct(arrays: ValueType[][]): ValueType[][] {
        return arrays.reduce((acc, curr) => {
            return acc.flatMap(c => curr.map(n => [...c, n]));
        }, [[]] as ValueType[][]);
    }

    function generatedSkus() {
        const groupChildren = specGroups.value
            .map(g => g.children)
            .filter(child => child.length > 0);

        if (groupChildren.length === 0) {
            skus.value = [];
            return;
        }

        const oldSkuMap = new Map<string, { price: number | '', stock: number | '' }>();
        skus.value.forEach(item => {
            const key = [...item._index].sort().join(',');
            oldSkuMap.set(key, {price: item.price, stock: item.stock});
        });


        const product = cartesianProduct(groupChildren);

        const beforeData = toRaw(skus.value);

        skus.value = product.map(combination => {

            const currentIndexes = combination.map(v => v._index);
            const lookupKey = [...currentIndexes].sort().join(',');

            const prevData = oldSkuMap.get(lookupKey);

            return {
                _index: currentIndexes, price: prevData ? prevData.price : '', stock: prevData ? prevData.stock : '',
            };
        });
    }

    function groupAdd() {
        const newGroup: GroupType = {
            _index: `g_${specGroups.value.length}`, name: '', children: [{
                name: '', _index: `v_${specGroups.value.length}_0` // 这里的对象符合 ValueType
            }]
        };
        specGroups.value.push(newGroup);
        generatedSkus();
    }

    function groupDelete(gIndex: number) {
        specGroups.value.splice(gIndex, 1);
        generatedSkus();
    }

    function valueAdd(gIndex: number) {
        const targetGroup = specGroups.value?.[gIndex];

        if (!targetGroup) {
            console.error(`Group index ${gIndex} not found.`);
            return;
        }

        const newValue: ValueType = {
            name: '',
            _index: `${targetGroup._index}_v${targetGroup.children.length}`
        };

        targetGroup.children.push(newValue);
        generatedSkus();
    }

    function valueDelete(gIndex: number, vIndex: number) {
        const targetGroup = specGroups.value?.[gIndex];
        if (!targetGroup || targetGroup.children.length <= 1) {
            console.warn("Cannot delete the last value in a group.");
            return;
        }

        targetGroup.children.splice(vIndex, 1);
        generatedSkus();
    }

    function attrName(item: GroupType, sku: SkuItem) {
        let name = '';
        item.children.forEach(_ => {
            if (sku._index.includes(_._index)) {
                name = _.name;
            }
        })
        return name;
    }

    watch(() => specGroups.value, (newVal) => {
        const groupNames = newVal.map(g => g.name.trim()).filter(Boolean)
        if (new Set(groupNames).size !== groupNames.length) {
            return ElMessage({
                message: '存在重复的规格名',
                type: 'error',
                plain: true,
            })
        }
        newVal.forEach((group, gIndex) => {
            const valueNames = group.children
                .map(v => v.name.trim())
                .filter(Boolean)

            if (new Set(valueNames).size !== valueNames.length) {
                return ElMessage({
                    message: `规格组 "${group.name}" 内存在重复的规格值`,
                    type: 'error',
                    plain: true,
                })
            }
        })
    }, {deep: true});
    return {
        specGroups,
        skus,
        generatedSkus,
        groupAdd,
        groupDelete,
        valueAdd,
        valueDelete,
        attrName,
    }
}

